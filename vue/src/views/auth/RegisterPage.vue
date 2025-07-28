<script>
import NoteIcon from '@shopify/polaris-icons/dist/svg/NoteIcon.svg';
import ViewIcon from '../../components/icons/ViewIcon';
import HideIcon from '../../components/icons/HideIcon'
import RegisterLayout from "@/layout/RegisterLayout.vue";
import RegisterPartOne from "@/components/misc/RegisterPartOne.vue";
import RegisterPartTwo from "@/components/misc/RegisterPartTwo.vue";
import common from "@/mixins/common";
export default {
  name: "RegisterPage",

  components: {
    RegisterLayout,
    RegisterPartOne,
    RegisterPartTwo
  },

  data() {
    return {
      options: [
        { "label": "eCommerce brand", "value": "eCommerce brand" },
        { "label": "Agency", "value": "Agency" },
        { "label": "Shopify app", "value": "Shopify app" },
      ],

      validImageTypes: ['image/gif', 'image/jpeg', 'image/png'],

      NoteIcon,
      ViewIcon,
      HideIcon,

      passHidden: true,
      passConfirmHidden: true,

      projectForm: {
        url: '',
        company_type: '',
        name: '',
        description: '',
        files: [],
      },

      userForm: {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: '',
        subscribe: true,
      },

      expertForm: {
        first_name: '',
        last_name: '',
        email: '',
        country: '',
        url: '',
        about: '',
        role: '',
        experience: '',
        availability: '',
        english_level: '',
        hourly_rate: '',
        subscribe: true,
      },

      page: 1,

      userType: 'expert' //expert|client
    }
  },

  mixins: [common],

  computed: {
    projectFormFill() {
      let isFilled = false;

      isFilled = this.projectForm.url && this.projectForm.company_type && this.projectForm.name && this.projectForm.description;

      return !isFilled;
    },

    userFormFill() {
      let matching, isFilled = false;

      matching = this.userForm.password && this.userForm.password_confirmation && this.userForm.password === this.userForm.password_confirmation;

      isFilled = this.userForm.first_name && this.userForm.email

      return !matching && !isFilled;
    }
  },

  methods: {
    nextPage(back = false) {
      if (back) {
        this.page = this.page - 1
      } else {
        this.page = this.page + 1
      }
    },

    getSource(file) {
      return window.URL.createObjectURL(file);
    },

    handleDropZoneDrop(_dropFiles, acceptedFiles) {
      this.projectForm.files.value = [...this.projectForm.files.value, ...acceptedFiles];
    },

    showPass() {
      this.passHidden = !this.passHidden;
    },

    showConfirmPass() {
      this.passConfirmHidden = !this.passConfirmHidden;
    }
  }
}
</script>

<template>
  <RegisterLayout>
    <template v-if="page === 1">
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        Step 1/2
      </Text>

      <BlockStack gap="300">
        <BlockStack gap="100">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            Submit your project
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            Connect with the world best Shopify developers.
          </Text>
        </BlockStack>
        <Text variant="bodyMd" as="p" alignment="start" tone="subdued">
          Already have an account?
          <Link :removeUnderline="true" external target="_blank" to="login" >Login</Link>
        </Text>
      </BlockStack>

      <BlockStack gap="800">
        <FormLayout>
          <TextField
              label="Your website"
              autoComplete="off"
              v-model="projectForm.url"
              placeholder="www.shopifystore.com"
              :error="null" />

          <TextField
              label="Project title"
              autoComplete="off"
              v-model="projectForm.description"
              placeholder="Pick a catchy title for your project"
              :error="null" />

          <Select
              label="Type of company"
              :options="options"
              placeholder="Select an option ..."
              v-model="projectForm.company_type"
          />

          <TextField
              label="About the project"
              autoComplete="off"
              v-model="projectForm.name"
              :multiline="5"
              placeholder="Please try to be as detailed as possible, as this helps us provide you with the best possible solutions ..."
              :error="null" />

          <DropZone label="Attach files" @drop="handleDropZoneDrop" variable-height>
            <LegacyStack v-if="projectForm.files.length" vertical>
              <LegacyStack
                  v-for="(file, index) in projectForm.files"
                  :key="index"
                  alignment="center"
              >
                <Thumbnail
                    size="small"
                    :alt="file.name"
                    :source="validImageTypes.includes(file.type) ? getSource(file) : NoteIcon"
                />
                <div>
                  {{ file.name }}{{ ' ' }}
                  <Text variant="bodySm" as="p">{{ file.size }} bytes</Text>
                </div>
              </LegacyStack>
            </LegacyStack>
            <DropZoneFileUpload v-else actionTitle="Add files" actionHint="Accept documents and images files" />
          </DropZone>
        </FormLayout>
      </BlockStack>

      <Button variant="primary" fullWidth @click="nextPage()">Next</Button>
    </template>

    <template v-else-if="page === 2">
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        Step 2/2
      </Text>

      <BlockStack gap="300">
        <BlockStack gap="100">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            Almost done!
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            We just need couple more details and you're all set!
          </Text>
        </BlockStack>
      </BlockStack>

      <BlockStack gap="800">
        <FormLayout>
          <FormLayoutGroup>
            <TextField
                label="First Name"
                autoComplete="off"
                v-model="userForm.first_name"
                placeholder="Enter your first name"
                :error="null" />

            <TextField
                label="Last Name"
                v-model="userForm.last_name"
                placeholder="Enter your last name"
                autoComplete="off" />
          </FormLayoutGroup>

          <TextField
              type="email"
              label="Email"
              autoComplete="email"
              v-model="userForm.email"
              placeholder="Enter your email" />


          <TextField
              :type="passHidden ? 'password' : 'text'"
              label="Password"
              v-model="userForm.password"
              placeholder="Enter your password"
              autoComplete="off"
          >
            <template v-slot:suffix>
              <Icon v-if="passHidden" :source="ViewIcon" tone="base" @click="showPass" style="cursor: pointer"/>
              <Icon v-else :source="HideIcon" tone="base" @click="showPass" style="cursor: pointer"/>
            </template>
          </TextField>

          <TextField
              :type="passConfirmHidden ? 'password' : 'text'"
              label="Password"
              v-model="userForm.password_confirmation"
              placeholder="Re-type your password"
              autoComplete="off"
          >
            <template v-slot:suffix>
              <Icon v-if="passConfirmHidden" :source="ViewIcon" tone="base" @click="showConfirmPass" style="cursor: pointer"/>
              <Icon v-else :source="HideIcon" tone="base" @click="showConfirmPass" style="cursor: pointer"/>
            </template>
          </TextField>

          <BlockStack gap="200">
            <Text variant="bodyMd" as="p" alignment="start">
              We'd love to send you our weekly newsletter with Shopify resources and occasional offers for Shopexperts services.
            </Text>

            <Checkbox
                label="Yes please, send me tips and offers via email."
                v-model="userForm.subscribe"
            />
          </BlockStack>
        </FormLayout>
      </BlockStack>

      <BlockStack gap="600">
        <Button variant="primary" fullWidth @click="nextPage()">Submit Project</Button>

        <Button fullWidth @click="nextPage(true)">Back</Button>

        <Text variant="bodyMd" as="p" alignment="center" tone="subdued">
          By procedding, you agree to the
          <Link :removeUnderline="true" external target="_blank" :url="clientTermsUrl">Terms & Conditions</Link> and
          <Link :removeUnderline="true" external target="_blank" :url="privacyUrl" >Privacy Policy</Link>.
        </Text>
      </BlockStack>
    </template>

    <template v-else-if="page === 3">
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        Step 1/4
      </Text>

      <BlockStack gap="100" style="margin-top: 16px">
        <Text variant="headingXl" fontWeight="bold" as="h2">
          You're interested in joining the Shopexperts talent network?
        </Text>
        <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
          We pledge to link freelancers to lucrative contracts with top eCommerce companies and support them in a growing global community of freelancers.
        </Text>
      </BlockStack>

      <BlockStack gap="400" style="margin-top: 16px">
        <RegisterPartOne style="max-width: fit-content"/>

        <Text variant="bodyLg" as="p" alignment="center">
          Before proceeding, ensure your technical and communication skills are proficient. We only accept freelancers with a <strong>minimum of 3 years of professional experience</strong> in development or design.
        </Text>
      </BlockStack>

      <Button variant="primary" fullWidth @click="nextPage()" style="margin-top: 16px">Next</Button>
    </template>

    <template v-else-if="page === 4">
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        Step 2/4
      </Text>

      <BlockStack gap="300">
        <BlockStack gap="100">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            Join Shopifyexperts talent network
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            This will likely take no more than 4 minutes.
          </Text>
        </BlockStack>
      </BlockStack>

      <BlockStack gap="800">
        <FormLayout>
          <FormLayoutGroup>
            <TextField
                label="First Name"
                autoComplete="off"
                v-model="expertForm.first_name"
                placeholder="Enter your first name"
                :error="null" />

            <TextField
                label="Last Name"
                v-model="expertForm.last_name"
                placeholder="Enter your last name"
                autoComplete="off" />
          </FormLayoutGroup>

          <TextField
              type="email"
              label="Email"
              autoComplete="email"
              v-model="expertForm.email"
              placeholder="Enter your email" />

          <TextField
              type="text"
              label="Country of residence"
              v-model="expertForm.country"
              placeholder="Select an option..." />

          <TextField
              type="text"
              label="LinkedIn/Portfolio website URL"
              v-model="expertForm.country"
              placeholder="www.myportfolio.com " />

          <TextField
              label="Tell us about your previous experience and skills as a Shopify expert especially as it relates to Shopify or eCommerce."
              autoComplete="off"
              v-model="expertForm.about"
              :multiline="5"
              placeholder="Please give as much detail as you can about previous duties and roles. Include programming languages, frameworks and unique skills or projects you've worked on."
              :error="null" />
        </FormLayout>
      </BlockStack>

      <Button variant="primary" fullWidth @click="nextPage()" style="margin-top: 16px">Next</Button>
    </template>

    <template v-else-if="page === 5">
      <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
        Step 3/4
      </Text>

      <BlockStack gap="300">
        <BlockStack gap="100">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            Almost done!
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            We just need couple more details and you're all set!
          </Text>
        </BlockStack>
      </BlockStack>

      <BlockStack gap="800">
        <FormLayout>
          <TextField
              type="text"
              label="Role"
              v-model="expertForm.role"
              placeholder="Your primary focus as Shopify expert..." />

          <TextField
              type="text"
              label="Years of experience"
              v-model="expertForm.experience"
              placeholder="Select an option..." />

          <TextField
              type="text"
              label="What is your availability per week?"
              v-model="expertForm.availability"
              placeholder="Select an option..."
              helpText="Freelancers typically need to commit to at least 10 hours per week."
          />

          <TextField
              type="text"
              label="What is your English level?"
              v-model="expertForm.english_level"
              placeholder="Select an option..." />

          <TextField
              type="text"
              label="Expected hourly rate"
              v-model="expertForm.hourly_rate"
              placeholder="Enter your hourly rate (USD)" />

          <BlockStack gap="200">
            <Text variant="bodyMd" as="p" alignment="start">
              We'd love to send you our weekly newsletter with Shopify resources and occasional offers for Shopexperts services.
            </Text>

            <Checkbox
                label="Yes please, send me tips and offers via email."
                v-model="userForm.subscribe"
            />
          </BlockStack>
        </FormLayout>
      </BlockStack>

      <BlockStack gap="600">
        <Button variant="primary" fullWidth @click="nextPage()">Submit Application</Button>

        <Button fullWidth @click="nextPage(true)">Back</Button>

        <Text variant="bodyMd" as="p" alignment="center" tone="subdued">
          By procedding, you agree to the
          <Link :removeUnderline="true" external target="_blank" :url="clientTermsUrl">Terms & Conditions</Link> and
          <Link :removeUnderline="true" external target="_blank" :url="privacyUrl" >Privacy Policy</Link>.
        </Text>
      </BlockStack>
    </template>

    <template v-else>
        <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
          Step 4/4
        </Text>

        <BlockStack gap="100" style="margin-top: 16px">
          <Text variant="headingXl" fontWeight="bold" as="h2">
            Application successfully submitted!
          </Text>
          <Text variant="bodyLg" as="p" alignment="start" tone="subdued">
            You can expect a follow up from us within 2-3 business days.
          </Text>
        </BlockStack>

        <BlockStack gap="400" style="margin-top: 16px">
          <RegisterPartTwo style="max-width: fit-content"/>

          <Text variant="bodyLg" as="p" alignment="center">
            Our team will thoroughly review your application details. We're excited about the possibility of welcoming you into our vetted network soon. Thanks for your patience!          </Text>
        </BlockStack>

        <Button fullWidth style="margin-top: 16px" @click="() => this.page = 1">Back to Website</Button>
      </template>
  </RegisterLayout>
</template>

<style scoped>
</style>