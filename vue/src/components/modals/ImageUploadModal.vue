<script>
import { CircleStencil, Cropper, Preview } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import MobileModal from "@/components/MobileModal.vue";
import CheckCircle from "@/components/icons/CheckCircle.vue";
import XIcon from "@/components/icons/XIcon.vue";
import UploadIcon from "@/components/icons/UploadIcon.vue";
import axios from "axios";
import InputBtn from "@/components/misc/InputBtn.vue";
import {debounce} from "@/directives/debounce";

function getMimeType(file, fallback = null) {
  const byteArray = (new Uint8Array(file)).subarray(0, 4);
  let header = '';
  for (let i = 0; i < byteArray.length; i++) {
    header += byteArray[i].toString(16);
  }
  switch (header) {
    case "89504e47":
      return "image/png";
    case "47494638":
      return "image/gif";
    case "ffd8ffe0":
    case "ffd8ffe1":
    case "ffd8ffe2":
    case "ffd8ffe3":
    case "ffd8ffe8":
      return "image/jpeg";
    default:
      return fallback;
  }
}
export default {
  name: "ImageUploadModal",

  components: {
    InputBtn,
    MobileModal,
    Cropper,
    // eslint-disable-next-line vue/no-unused-components
    CircleStencil,
    Preview,
  },

  data() {
    return {
      UploadIcon,
      CheckCircle,
      XIcon,
      isMobile: screen.width <= 760,

      loading: false,

      image: {
        src: null,
        type: null
      },
      result: {
        coordinates: null,
        image: null
      }
    }
  },
  methods: {
    onChange({ coordinates, image }) {
      this.result = {
        coordinates,
        image
      };
    },

    reset() {
      this.image = {
        src: null,
        type: null
      }
    },

    loadImage($event) {
      // Reference to the DOM input element
      const { files } = $event.target;
      // Ensure that you have a file before attempting to read it
      if (files && files[0]) {
        // 1. Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.image.src) {
          URL.revokeObjectURL(this.image.src)
        }
        // 2. Create the blob link to the file to optimize performance:
        const blob = URL.createObjectURL(files[0]);

        // 3. The steps below are designated to determine a file mime type to use it during the
        // getting of a cropped image from the canvas. You can replace it them by the following string,
        // but the type will be derived from the extension and it can lead to an incorrect result:
        //
        // this.image = {
        //    src: blob;
        //    type: files[0].type
        // }

        // Create a new FileReader to read this image binary data
        const reader = new FileReader();
        // Define a callback function to run, when FileReader finishes its job
        reader.onload = (e) => {
          // Note: arrow function used here, so that "this.image" refers to the image of Vue component
          this.image = {
            // Set the image source (it will look like blob:http://example.com/2c5270a5-18b5-406e-a4fb-07427f5e7b94)
            src: blob,
            // Determine the image type to preserve it during the extracting the image from canvas:
            type: getMimeType(e.target.result, files[0].type),
          };
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsArrayBuffer(files[0]);
      }
    },

    uploadImage: debounce(function () {
      const { canvas } = this.$refs.cropper.getResult();
      if (canvas) {
        this.loading = true;
        const form = new FormData();
        canvas.toBlob(blob => {
          form.append('file', blob);
          // You can use axios, superagent and other libraries instead here

          axios.post('api/picture', form, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }).then(() => {
            this.loading = false;
            this.close();
          }).catch(err => {
            console.log(err)
            this.loading = false;
          })
          // Perhaps you should add the setting appropriate file format here
        }, 'image/jpeg');
      }
    }, 200),

    close() {
      if (this.image.src) {
        URL.revokeObjectURL(this.image.src)
      }

      this.$emit('close')
    }
  },

  unmounted() {
    // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
    if (this.image.src) {
      URL.revokeObjectURL(this.image.src)
    }
  }
}
</script>

<template>
  <template v-if="isMobile">
    <MobileModal>
      <template #heading>
        <InlineStack align="space-between" blockAlign="start" :wrap="false">
          <Text variant="bodyLg" fontWeight="bold" as="p">
            Upload Profile Picture
          </Text>

          <div>
            <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
          </div>
        </InlineStack>
      </template>

      <Box
          borderBlockStartWidth="0"
          borderBlockEndWidth="025"
          borderInlineStartWidth="0"
          borderInlineEndWidth="0"
          borderColor="border"
          padding="400">

        <BlockStack v-if="image.src" gap="200">
          <cropper
              class="cropper-mobile"
              :src="image.src"
              ref="cropper"
              :stencil-component="$options.components.CircleStencil"
              @change="onChange"
          />

          <InlineStack gap="300" inlineAlign="end">
            <preview
                class="preview"
                :width="80"
                :height="80"
                :image="result.image"
                :coordinates="result.coordinates"
            />

            <preview
                class="preview"
                :width="60"
                :height="60"
                :image="result.image"
                :coordinates="result.coordinates"
            />
          </InlineStack>
        </BlockStack>

        <Text v-else>Click on "Upload Photo" to start editing your profile picture</Text>
      </Box>

      <template #footer>
        <BlockStack gap="300">
          <InlineStack align="start" gap="200">
            <Button tone="success" :icon="UploadIcon" variant="primary" @click.stop="$refs.file.click()">
              <input style="display: none" type="file" ref="file" @change="loadImage($event)" accept="image/*">
              Upload Photo
            </Button>
          </InlineStack>


          <InlineStack align="end" gap="200">
            <Button @click="close">Cancel</Button>


            <InputBtn :icon="CheckCircle"  :loading="loading" @click="uploadImage">Save As Profile Photo</InputBtn>
          </InlineStack>
        </BlockStack>
      </template>
    </MobileModal>
  </template>

  <template v-else>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; background: #00000033">
      <BlockStack inlineAlign="center" align="center" style="height: 100%">
        <Card style="width: 720px;" :padding="null">
          <Box background="bg-surface-secondary"
               borderBlockStartWidth="0"
               borderBlockEndWidth="025"
               borderInlineStartWidth="0"
               borderInlineEndWidth="0"
               borderColor="border"
               paddingBlock="300"
               paddingInline="400">
            <InlineStack align="space-between">
              <Text variant="bodyLg" fontWeight="bold" as="p">
                Upload Profile Picture
              </Text>

              <div>
                <Icon :source="XIcon"  @click="close"/>
              </div>
            </InlineStack>
          </Box>
          <Box
              borderBlockStartWidth="0"
              borderBlockEndWidth="025"
              borderInlineStartWidth="0"
              borderInlineEndWidth="0"
              borderColor="border"
              padding="400">

            <InlineStack v-if="image.src" align="space-between">
              <cropper
                  class="cropper"
                  :src="image.src"
                  ref="cropper"
                  :stencil-component="$options.components.CircleStencil"
                  @change="onChange"
              />

              <BlockStack gap="600" inlineAlign="end">
                <preview
                    class="preview"
                    :width="80"
                    :height="80"
                    :image="result.image"
                    :coordinates="result.coordinates"
                />

                <preview
                    class="preview"
                    :width="60"
                    :height="60"
                    :image="result.image"
                    :coordinates="result.coordinates"
                />
              </BlockStack>
            </InlineStack>

            <Text v-else>Click on "Upload Photo" to start editing your profile picture</Text>
          </Box>

          <Box padding="400">
            <InlineStack align="space-between">
              <Button tone="success" :icon="UploadIcon" variant="primary" @click.stop="$refs.file.click()">
                <input style="display: none" type="file" ref="file" @change="loadImage($event)" accept="image/*">
                Upload Photo
              </Button>

              <InlineStack align="end" gap="200">
                <Button @click="close">Cancel</Button>
                <InputBtn :icon="CheckCircle"  :loading="loading" @click="uploadImage">Save As Profile Photo</InputBtn>
              </InlineStack>
            </InlineStack>
          </Box>
        </Card>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>
.cropper {
  height: 500px;
  width: 500px;
  background: #DDD;
}
.cropper-mobile {
  height: 300px;
  width: 500px;
  background: #DDD;
}
.preview {
  border-radius: 100%;
}
</style>