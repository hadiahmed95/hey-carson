<template>
  <div :class="[
  'gap-8',
  isOnboarding ? 'grid grid-cols-1 md:grid-cols-3' : 'w-full',
      !isOnboarding && 'md:col-span-3'
    ]">
    <div v-if="isOnboarding">
      <h3 class="font-semibold mb-2">Personal & Business Details</h3>
      <h4 class="mb-3">Give potential clients a peek at your personal and business
        <br>story. Some fields are locked on the free plan - upgrade to
        <br>unlock them all!
      </h4>
      <button class="text-h5 font-medium px-2 py-1 bg-primary text-white rounded-sm mb-4">
        Upgrade Plan
      </button>
      <h5 class="font-light p-4 bg-accent rounded-sm mb-4">
        A paid profile gives you many more options to stand out in the directory and attract more leads. It also makes you part of the <span class="font-semibold">shopexperts Premium Partner Program</span>.
      </h5>
    </div>
    <div :class="[
        isOnboarding ? 'md:col-span-2' : 'w-full',
        'space-y-4 bg-white rounded-md border border-gray p-5'
      ]">
      <div class="space-y-14">
        <h3 class="font-semibold border-b border-grey pb-2">Personal & Business Details</h3>
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <img src="https://randomuser.me/api/portraits/men/10.jpg" alt="Avatar" class="w-18 h-18 rounded-full object-cover" />
            <div class="flex flex-col gap-1">
              <h4 class="font-semibold">Profile Picture</h4>
              <h4 class="font-normal">PNG, JPG, GIF under 15MB.</h4>
            </div>

          </div>
          <div class="flex items-center gap-2">

            <button class="text-h5 font-normal px-3 py-1 bg-primary text-white rounded-sm flex items-center gap-1">
              <Upload/>
              Upload Image
            </button>
            <button class="text-h5 font-normal px-3 py-1 text-primary border border-grey rounded-sm flex items-center gap-1">
              <Delete/>
              Delete
            </button>
          </div>
        </div>
        <div class="flex flex-col gap-6">
          <div class="flex items-center gap-6 w-full">
            <div class="w-1/2">
              <label class="text-h5 font-sm block font-archivo text-primary mb-1">First Name</label>
              <input type="text" class="text-paragraph w-full border px-4 py-2 rounded-md text-primary font-archivo " placeholder="John" value="John" />
            </div>
            <div class="w-1/2">
              <label class="text-h5 font-sm block font-archivo text-primary mb-1">Last Name</label>
              <input type="text" class="border w-full px-4 py-2 rounded-md text-primary font-archivo text-paragraph" placeholder="John" value="John" />
            </div>
          </div>
          <div class="flex items-center gap-6 w-full">
            <div class="w-1/2">
              <label class="block text-h5 font-sm font-archivo text-primary mb-1">Role</label>
              <select class='w-full border px-4 py-2 rounded-md text-primary'>
                <option disabled value="">Select Role</option>
                <option value="Shopify Developer">Shopify Developer</option>
                <option value="Designer">Designer</option>
              </select>
            </div>
            <div class="w-1/2">
              <label class="text-h5 font-sm block font-archivo text-primary mb-1">Starting Price (USD)</label>
              <input type="text" class="border w-full px-4 py-2 rounded-md text-primary font-archivo text-paragraph" placeholder="85" value="85" />
            </div>
          </div>
          <div class="flex items-center gap-6 w-full">
            <div class="w-1/2">
              <label class="block text-h5 font-sm font-archivo text-primary mb-1">Location</label>
              <select class='w-full border px-4 py-2 rounded-md text-primary'>
                <option disabled value="">Select Country</option>
                <option value="Argentina">Argentina</option>
                <option value="America">America</option>
              </select>
            </div>
            <div class="w-1/2">
              <label class="block text-h5 font-sm font-archivo text-primary mb-1">Timezone</label>
              <select class='w-full border px-4 py-2 rounded-md text-primary'>
                <option disabled value="">Select Timezone</option>
                <option value="GMT (-3:00)">GMT (-3:00)</option>
                <option value="GMT (-1:00)">GMT (-1:00)</option>
              </select>
            </div>
          </div>
          <div>
            <label class="text-h5 font-sm block font-archivo text-primary mb-1">Business address</label>
            <input type="text" class="border w-full px-4 py-2 rounded-md text-primary font-archivo text-paragraph" placeholder="Calle Paraguay 4500, Belgrano, Buenos Aires, Argentina" value="Calle Paraguay 4500, Belgrano, Buenos Aires, Argentina" />
          </div>
          <div>
            <label class="block text-h5 font-sm font-archivo text-primary mb-1" for="bio">Short Bio</label>
            <textarea
                id="bio"
                rows="4"
                class="w-full border border-grey rounded-md px-4 py-2 text-paragraph text-primary font-archivo"
            >I’m Lautaro Climent, a Shopify expert with over 9 years of experience helping e-commerce businesses succeed. I specialize in custom store setups, theme development, and app integrations, combining technical skills with creative problem-solving. My goal is to optimize store performance, enhance user experience, and deliver results that exceed expectations—whether it’s a quick fix or a full-scale project</textarea>
          </div>
          <div class="flex items-center gap-6 w-full">
            <div class="w-1/2">
              <div class="flex justify-between items-center mb-1">
                <div class="flex items-center gap-2">
                  <Lock v-if="!isOnboarding" />
                  <label class="text-h5 font-sm font-archivo text-primary">Website</label>
                </div>
                <div
                    v-if="!isOnboarding"
                    class="flex items-center bg-primary text-white rounded-md px-2 py-[2px] text-xs font-semibold"
                >
                  LOCKED
                </div>
              </div>

              <input
                  type="text"
                  class="text-paragraph w-full border px-4 py-2 rounded-md text-primary font-archivo"
                  value="www.lautarodeveloper.com"
              />
            </div>
            <div class="w-1/2">
              <div class="flex justify-between items-center mb-1">
                <div class="flex items-center gap-2">
                  <Lock v-if="!isOnboarding" />
                  <label class="text-h5 font-sm font-archivo text-primary">Phone number</label>
                </div>
                <div
                    v-if="!isOnboarding"
                    class="flex items-center bg-primary text-white rounded-md px-2 py-[2px] text-xs font-semibold"
                >
                  LOCKED
                </div>
              </div>
              <input
                  type="text"
                  class="text-paragraph w-full border px-4 py-2 rounded-md text-primary font-archivo"
                  value="+54 11 4321-9876"
              />
            </div>
          </div>
          <h5 class="font-medium">Other Links</h5>
          <div v-for="field in links" :key="field.label" class="mt-4">
            <div class="flex justify-between items-center mb-1">
              <div class="flex items-center gap-2">
                <Lock v-if="!isOnboarding" />
                <label class="text-h5 font-sm font-archivo text-primary">{{ field.label }}</label>
              </div>
              <div
                  v-if="!isOnboarding"
                  class="flex items-center bg-primary text-white rounded-md px-2 py-[2px] text-xs font-semibold"
              >
                LOCKED
              </div>
            </div>
            <input
                type="text"
                class="text-paragraph w-full border px-4 py-2 rounded-md text-primary font-archivo"
                :value="field.value"
            />
          </div>
          <div>
            <label class="block text-h5 font-sm font-archivo text-primary mb-1">Regions Served</label>
            <select class='w-full border px-4 py-2 rounded-md text-tertiary mb-3'>
              <option value="">Select from the list ...</option>
              <option value="United States">United States</option>
              <option value="Canada">Canada</option>
              <option value="Australia">Australia</option>
              <option value="Singapore">Singapore</option>
              <option value="Germany">Germany</option>
            </select>
            <div class="flex items-center gap-2">
              <div class="border border-grey text-primary py-[2px] px-3 rounded-full">United States</div>
              <div class="border border-grey text-primary py-[2px] px-3 rounded-full">Canada</div>
              <div class="border border-grey text-primary py-[2px] px-3 rounded-full">Australia</div>
              <div class="border border-grey text-primary py-[2px] px-3 rounded-full">Singapore</div>
              <div class="border border-grey text-primary py-[2px] px-3 rounded-full">Germany</div>
            </div>
          </div>
          <div>
            <label class="block text-h5 font-sm font-archivo text-primary mb-1">Languages</label>
            <select class='w-full border px-4 py-2 rounded-md text-tertiary mb-3'>
              <option value="">Select from the list ...</option>
              <option value="English">English</option>
              <option value="Spanish">Spanish</option>
              <option value="French">French</option>
              <option value="Arabic">Arabic</option>
            </select>
            <div class="flex items-center gap-2">
              <div class="border border-grey text-primary py-[2px] px-3 rounded-full">English</div>
              <div class="border border-grey text-primary py-[2px] px-3 rounded-full">Spanish</div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import Upload from "../../assets/icons/upload.svg"
import Delete from "../../assets/icons/delete.svg"
import Lock from "../../assets/icons/lock.svg"

const links = [
  {
    label: "Linkedin profile",
    value: "https://www.linkedin.com/in/lautaro-climent",
  },
  {
    label: "Github profile",
    value: "https://github.com/lautaro-climent",
  },
  {
    label: "X profile",
    value: "https://x.com/lautaro-climent",
  },
  {
    label: "Fiveer profile",
    value: "https://www.fiverr.com/lautaro-climent",
  },
  {
    label: "Upwork profile",
    value: "https://www.upwork.com/freelancers/lautaro-climent",
  },
];

const { isOnboarding = true } = defineProps<{
  isOnboarding?: boolean
}>()
</script>
