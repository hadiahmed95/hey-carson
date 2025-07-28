<?php

namespace App\Http\Controllers;

use App\Lib\Postmark\Inbound;
use App\Lib\Postmark\InboundException;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InboundEmailsController extends Controller
{
    private Inbound $inbound;
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws InboundException
     */
    public function inboundData(Request $request): JsonResponse
    {
        $request->merge([
            'runObserver' => true,
            'inboundData' => true
        ]);

        $this->inbound = new Inbound(json_encode(request()->all()));
        $subject = rtrim($this->inbound->Subject());

        try {
            if (!Str::endsWith($subject, 'You’ve received a new message')) {
                throw new InboundException('Subject did not match.');
            }

            Log::channel('inboundEmails')->info('Inbound email received');

            list (
                $projectId,
                $messageId,
                $sender,
                $repliedMessageContent
                ) = $this->extractMessageData();

            Log::channel('inboundEmails')->info('Processed data: ', [
                $messageId,
                $projectId,
                $sender,
                $repliedMessageContent
            ]);

            $repliedTextMessage = Message::create([
                'content' => $repliedMessageContent,
                'type' => "text",
                'project_id' => $projectId,
                'user_id' => $sender->id,
                'user_type' => $sender->role_id === 2 ? 'client' : 'expert',
                'seen' => false,
                'sent_from_email' => true,
                'reply_id' => $messageId,
            ]);

            Log::channel('inboundEmails')->info('Replied text message saved!');

            if (!empty($this->inbound->Attachments()->attachments)) {
                $this->processAttachments($projectId, $repliedTextMessage->id);
            } else {
                Log::channel('inboundEmails')->info('No attachments received!');
            }

            return response()->json([
                'status' => true
            ], 200);


        } catch (\Exception $e) {
            Log::channel('inboundEmails')->error('Inbound Email Processing Failed:', [
                'messageId' => $messageId ?? null,
                'project_id' => $projectId ?? null,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 200);
        }
    }

    private function processAttachments($projectId, $messageId): void
    {
        try {
            Log::channel('inboundEmails')->info('Processing attachments!');

            $dir = dirname(__FILE__) . '/';
            $attachments = $this->inbound->Attachments();

            foreach ($attachments as $index => $attachment) {
                Log::channel('inboundEmails')->info('Start processing attachments: ' . $index);

                $filename = $attachment->Name;
                $tempArray = explode('.', $filename);
                $fileType = $tempArray[count($tempArray) - 1];
                $attachment->Download($dir);
                $localFilePath = $dir . $filename;

                if (file_exists($localFilePath)) {
                    try {
                        \DB::beginTransaction();

                        $path = 'messages/' . $projectId . '/' . $messageId;

                        Log::channel('inboundEmails')->info("Uploading {$index} on s3! : " . $path);

                        Storage::disk('s3')->put($path . '/' . $filename, file_get_contents($localFilePath));

                        Log::channel('inboundEmails')->info('Uploaded on s3! : ' . $index);

                        unlink($localFilePath);
                        $project = Project::find($projectId);

                        $project->projectFiles()->create([
                            'message_id' => $messageId,
                            'name' => $filename,
                            'url' => $path . '/' . $filename
                        ]);

                        Log::channel('inboundEmails')->info('Uploaded file: ' . $filename, [
                            'filename' => $filename,
                        ]);

                        \DB::commit();
                    } catch (\Exception $e) {
                        \DB::rollBack();
                        // Catch specific exceptions and log them
                        Log::channel('inboundEmails')->error("Exception during file {$index} processing: " . $e->getMessage(), [
                            'attachment' => $filename,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
            Log::channel('inboundEmails')->info('End file processing! ');
        } catch (\Exception $e) {
            Log::channel('inboundEmails')->error('Image Processing Failed:', []);
        }
    }

    /**
     * @throws InboundException
     */
    private function extractMessageData(): array
    {
        Log::channel('inboundEmails')->info('Extracting Message Data...');

        $senderEmail = $this->inbound->FromEmail();
        $strippedTextReply = $this->inbound->StrippedTextReply();
        $inReplyTo = $this->inbound->Headers('In-Reply-To');
        $references = $this->inbound->Headers('References');
        $htmlBody = $this->inbound->HtmlBody();


        list($projectId, $messageId) = $this->getMessageIdAndProjectId(
            $inReplyTo,
            $references,
            $htmlBody
        );

        return [
            $projectId,
            $messageId,
            $this->getSenderUserByEmail($senderEmail),
            $this->getMessageContent($strippedTextReply, $htmlBody)
        ];
    }

    /**
     * Finds a sender(Client or Expert) by their email address.
     *
     * @param string $senderEmail The email address of the user to search for.
     *
     * @return User
     */
    private function getSenderUserByEmail(string $senderEmail): User
    {
        return $this->userRepository->findByEmail($senderEmail);
    }

    /**
     * Retrieves the message content, prioritizing the reply text over HTML content.
     *
     * @param string $strippedTextReply The reply text in the email.
     * @param string $htmlBody The HTML body of the email.
     *
     * @return string The message content (either reply text or extracted from HTML).
     * @throws InboundException
     */
    private function getMessageContent(string $strippedTextReply, string $htmlBody): string
    {
        return $strippedTextReply ?: $this->extractReplyText($htmlBody);
    }

    /**
     * Attempts to extract the project ID and message ID from headers or fallback HTML.
     *
     * @param string|null $inReplyTo
     * @param string|null $references
     * @param string|null $htmlBody
     * @return array [projectId, messageId] or [null, null]
     */
    private function getMessageIdAndProjectId(?string $inReplyTo, ?string $references, ?string $htmlBody): array
    {
        if ($inReplyTo && preg_match('/<project-(\d+)-message-(\d+)@[^>]+>/', $inReplyTo, $matches)) {
            return [(int) $matches[1], (int) $matches[2]];
        }

        if ($references && preg_match('/<project-(\d+)-message-(\d+)@[^>]+>/', $references, $matches)) {
            return [(int) $matches[1], (int) $matches[2]];
        }

        if ($htmlBody && preg_match('/\/(client|expert)\/project\/(\d+)\?messageId=(\d+)/', $htmlBody, $matches)) {
            return [(int) $matches[2], (int) $matches[3]];
        }

        return [null, null];
    }

    /**
     * Extracts reply text from the provided HTML.
     *
     * @param string $html The HTML content to extract the reply text from.
     *
     * @return string The extracted and cleaned reply text, or 'Reply Not Found' if not found.
     * @throws InboundException If the TextReply is not found.
     */
    private function extractReplyText(string $html): string
    {

        $pattern = strpos($html, '<html>') === false
            ? '/<div\s+dir="ltr">(.+?)(?=<div\s+(class="|dir="|id="))/is'
            : '/<body[^>]*>(.*?)<div\s+(dir="ltr"|class=")|<div\s+dir="ltr">/is';

        if (preg_match($pattern, $html, $matches) && isset($matches[1])) {

            [$parsedContent] = $matches;

            $cleanedContent = preg_replace(
                [
                    '/<div(.*?)>/',
                    '/<\/div>/',
                    '/<br\s+id="[^"]*"\s*\/?>/',
                    '/ dir="ltr"/',
                    '/\x{00A0}/u',
                    '/&nbsp;/i'
                ],
                [
                    '<p$1>',
                    '</p>',
                    '<br>',
                    '',
                    ' ',
                    ' '
                ],
                $parsedContent
            );

            if ($cleanedContent === "<p><br></p><br>") {
                if (empty($this->inbound->Attachments())) {
                    throw new InboundException('Empty Message "<p><br></p><br>" found.');
                }
            }

            return $cleanedContent;
        }

        throw new InboundException('Reply Text not found in the provided HTML body.');
    }

}
