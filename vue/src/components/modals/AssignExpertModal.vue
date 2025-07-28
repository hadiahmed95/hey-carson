<script>

import XIcon from "@/components/icons/XIcon.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";
import axios from "axios";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import SelectPopover from "@/components/misc/SelectPopover.vue";
import TagBtn from "@/components/misc/TagBtn.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import LoadingSpinner from '@/components/misc/LoadingSpinner.vue';
import {debounce} from "@/directives/debounce";

export default {
  name: "AddTeamMemberModal",
  components: { InputBtn, TagBtn, SelectPopover, AvatarFrame, LoadingSpinner},
  props: {
    projectId: {
      default: null,
      type: Number,
    }
  },

  data() {
    return {
      XIcon,
      SearchIcon,

      isMobile: screen.width <= 760,

      experts: [],

      selectedExpert: null,

      pageLoading: true,
      search: '',

      activeRole: null,
      activeExp: null,
      activeLang: null,
      activeAvailability: null,
      activeHour: null,
      spinnerLoading: false,
      pageCount: 1,
      lastPage: 1,
      roles: [
        {
          name: 'Designer'
        },
        {
          name: 'Frontend Developer'
        },
        {
          name: 'Backend Developer'
        },
      ],
      experiences: [
        {
          name: 'Less than a year'
        },
        {
          name: '1-3 years'
        },
        {
          name: '3-5 years'
        },
        {
          name: '5-10 years'
        },
        {
          name: '10+ years'
        }
      ],
      english: [
        {
          name: 'Basic'
        },
        {
          name: 'Conversational'
        },
        {
          name: 'Fluent'
        },
        {
          name: 'Native'
        },
      ],
      availabilities: [
        {
          name: '10-20 hours per week',
        },
        {
          name: '20-30 hours per week',
        },
        {
          name: '30-40 hours per week',
        },
        {
          name: '40+ hours per week'
        }
      ],
      hours: [
        {
          name: 'Less than $60 per hour',
          from: null,
          to: 59.99,
        },
        {
          name: '$60-$75 per hour',
          from: 60.00,
          to: 74.99
        },
        {
          name: '$75-$90 per hour',
          from: 75.00,
          to: 89.99
        },
        {
          name: '$90-$115 per hour',
          from: 90.00,
          to: 114.99
        },
        {
          name: 'More than $115 per hour',
          from: 115.00,
          to: null
        },
      ],
    }
  },

  watch: {
    search: debounce(function () {
      this.defaultPageSettings();
      this.getExperts(true);
    }, 400),
  },

  async mounted() {
    await this.getExperts()
  },

  methods: {
    async getExperts(setDefaultSettings = false) {
      if (setDefaultSettings)
        this.defaultPageSettings()

      this.pageLoading = true
      this.spinnerLoading = true

      let params = {
        filter: {},
        status: 'active',
        projectId: this.projectId,
      }

      if (this.search) {
        params.search = this.search
      }

      if (this.activeRole) {
        params.filter.role = this.activeRole.name
      }
      if (this.activeExp) {
        params.filter.experience = this.activeExp.name
      }
      if (this.activeLang) {
        params.filter.eng_level = this.activeLang.name
      }
      if (this.activeAvailability) {
        params.filter.availability = this.activeAvailability.name
      }
      if (this.activeHour) {
        params.filter.hourly_rate = {
          from: this.activeHour.from,
          to: this.activeHour.to
        }
      }

      await axios.get(`api/admin/experts?page=${this.pageCount}`, {params}).then(res => {
        this.experts.push(...res.data.experts.data)
        this.expertsCount = res.data.experts_count
        this.pageCount += 1
        this.lastPage = res.data.experts.last_page

      }).catch(err => {
        console.log(err)
      }).finally(() => {
        this.pageLoading = false
        this.spinnerLoading = false
      })
    },

    defaultPageSettings() {
      this.pageCount = 1
      this.experts = []
    },

    loadMore() {
      if (this.pageCount > this.lastPage)
        return
      if (this.experts.length === 0)
        return

      this.getExperts()
    },

    setActiveRole(item) {
      this.activeRole = item
      this.getExperts(true)
    },

    setActiveExp(item) {
      this.activeExp = item
      this.getExperts(true)
    },

    setActiveLang(item) {
      this.activeLang = item
      this.getExperts(true)
    },

    setActiveAvailability(item) {
      this.activeAvailability = item
      this.getExperts(true)
    },

    setActiveHour(item) {
      this.activeHour = item
      this.getExperts(true)
    },

    clearAll() {
      this.activeRole = null;
      this.activeExp = null;
      this.activeLang = null;
      this.activeAvailability = null;
      this.activeHour = null

      this.getExperts(true)
    },

    selectExpert(expert) {
      if (this.selectedExpert && this.selectedExpert.id === expert.id) {
        this.selectedExpert = null
      } else {
        this.selectedExpert = expert
      }
    },

    assignProject() {
      let userId = this.selectedExpert.id;
      this.selectedExpert = null;
      this.$emit('assignProject', {userId})
    }
  }
}
</script>

<template>
    <div style="position: fixed; overflow-y: hidden; top: 0; left: 0; width: 100%; height: 100%; z-index: 200; background: #00000033"
         @click="() => this.$emit('close')">
      <BlockStack inlineAlign="center" align="center" style="height: 100%">
        <Card style="width: 620px;" :padding="null" @click.stop="null">
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
                Assign project to specific expert
              </Text>

              <div>
                <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
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
            <BlockStack gap="400">
              <Box style="padding: 16px">
                <BlockStack gap="300">
                  <TextField
                      style="min-width: 220px"
                      :label="null"
                      type="text"
                      v-model="search"
                      autoComplete="off"
                      placeholder="Search expert ..."
                  >
                    <template #prefix>
                      <Icon :source="SearchIcon" />
                    </template>
                  </TextField>

                  <BlockStack gap="300">
                    <InlineStack gap="100">
                      <SelectPopover name="Role"
                                     :active="activeRole"
                                     :items="roles"
                                     @selected="(item) => setActiveRole(item)" />
                      <SelectPopover name="Experience"
                                     :active="activeExp"
                                     :items="experiences"
                                     @selected="(item) => setActiveExp(item)" />
                      <SelectPopover name="English Level"
                                     :active="activeLang"
                                     :items="english"
                                     @selected="(item) => setActiveLang(item)" />
                      <SelectPopover name="Availability"
                                     :active="activeAvailability"
                                     :items="availabilities"
                                     @selected="(item) => setActiveAvailability(item)" />
                      <SelectPopover name="Hourly Rate"
                                     :active="activeHour"
                                     :items="hours"
                                     @selected="(item) => setActiveHour(item)" />

                      <Button variant="tertiary" @click="clearAll">Clear all</Button>
                    </InlineStack>

                    <InlineStack gap="100">
                      <TagBtn name="Role"
                              :item="activeRole"
                              @remove="() => setActiveRole(null)"
                              v-if="activeRole" />

                      <TagBtn name="Experience"
                              :item="activeExp"
                              @remove="() => setActiveExp(null)"
                              v-if="activeExp" />

                      <TagBtn name="English Level"
                              :item="activeLang"
                              @remove="() => setActiveLang(null)"
                              v-if="activeLang" />

                      <TagBtn name="Availability"
                              :item="activeAvailability"
                              @remove="() => setActiveAvailability(null)"
                              v-if="activeAvailability" />

                      <TagBtn name="Hourly Rate"
                              :item="activeHour"
                              @remove="() => setActiveHour(null)"
                              v-if="activeHour" />
                    </InlineStack>
                  </BlockStack>
                </BlockStack>
              </Box>

              <BlockStack gap="050" style="max-height: 275px; overflow: scroll" v-infinite-scroll="{ threshold: 1, callback: loadMore }">
                <Box @click="expert.availability_status !== 'unavailable' ? selectExpert(expert) : null"
                     :style="{
                      padding: '8px',
                      background: selectedExpert && selectedExpert.id === expert.id ? '#f1f1f1' : 'transparent',
                      opacity: expert.availability_status === 'unavailable' ? '0.5' : '1',
                    }"
                     borderColor="border" borderBlockEndWidth="025"
                     v-for="expert in experts" :key="expert.id">
                  <InlineStack gap="400" blockAlign="center" style="cursor: pointer;">
                    <div>
                      <AvatarFrame rounded size="lg" :user="expert" />
                    </div>

                    <BlockStack gap="050">
                      <Text variant="bodyMd" fontWeight="bold" as="h3" style="padding: 4px 0 0 0">
                        {{ expert.first_name }} {{ expert.last_name }} {{expert.availability_status === 'unavailable' ? ' [Not Available]' : ''}}
                      </Text>
                      <Text variant="bodyMd" tone="subdued" as="h3" style="padding: 0 0 4px 0">
                        {{ expert.email }}
                      </Text>
                    </BlockStack>
                  </InlineStack>
                </Box>
              </BlockStack>
              <LoadingSpinner :isLoading="spinnerLoading" />
            </BlockStack>
          </Box>

          <Box padding="400">
            <InlineStack align="end" gap="200">
              <Button @click="() => this.$emit('close')">Cancel</Button>

              <InputBtn :disabled="!selectedExpert" @click="assignProject">Done</InputBtn>
            </InlineStack>
          </Box>
        </Card>
      </BlockStack>
    </div>
</template>

<style scoped>
</style>