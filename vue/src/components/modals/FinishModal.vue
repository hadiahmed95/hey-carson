<script>
import CheckCircle from "@/components/icons/CheckCircle.vue";
import XIcon from "@/components/icons/XIcon.vue";
import MobileModal from "@/components/MobileModal.vue";
import UserBox from "@/components/misc/UserBox.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import StarRating from "@/components/misc/StarRating.vue";

export default {
  name: "ScopeModal",

  props: {
    project: {
      type: Object,
      default: () => {}
    }
  },

  components: {
    InputBtn,
    UserBox,
    MobileModal,
    StarRating
  },

  data() {
    return {
      CheckCircle,
      XIcon,
      isMobile: screen.width <= 760,

      review: {
        comment: '',
        timeToStart: 0,
        quality: 0,
        communication: 0,
        valueForMoney: 0,
        recommendation: null
      },
      selectedValueRange: null,
      valueRangeOptions: [
        { value: 'under_100', label: 'Under $100' },
        { value: '100_1000', label: '$100 - $1,000' },
        { value: '1000_5000', label: '$1,000 - $5,000' },
        { value: '5000_20000', label: '$5,000 - $20,000' },
        { value: '20000_100000', label: '$20,000 - $100,000' },
        { value: 'over_100000', label: '$100,000+' }
      ],
      recommendationOptions: [
        { value: 'very_likely', label: 'Very Likely' },
        { value: 'neutral', label: 'Neutral' },
        { value: 'not_very_likely', label: 'Not Very Likely' }
      ],
      finished: false,
    }
  },

  computed: {
    totalScore() {
      const sum = this.review.timeToStart + this.review.quality +
          this.review.communication + this.review.valueForMoney;
      return parseFloat((sum / 4).toFixed(2));
    }
  },

  methods: {
    markFinished() {
      const ratingsValid = this.review.timeToStart > 0 &&
          this.review.quality > 0 &&
          this.review.communication > 0 &&
          this.review.valueForMoney > 0;

      const allFieldsValid = ratingsValid &&
          this.review.comment.length > 4 &&
          this.review.recommendation !== null &&
          this.selectedValueRange !== null;

      if (allFieldsValid && !this.finished) {
        this.finished = true;
        this.$emit('createReview', {
          ...this.review,
          rate: this.totalScore,
          valueRange: this.selectedValueRange,
          recommendation: this.review.recommendation
        });
      }
    }
  }
}
</script>

<template>
  <template v-if="isMobile">
    <MobileModal style="overflow: scroll">
      <template #heading>
        <InlineStack align="space-between" blockAlign="start" :wrap="false">
          <Text variant="bodyLg" fontWeight="bold" as="p">
            Mark this project as completed
          </Text>

          <div>
            <Icon :source="XIcon"  @click="() => this.$emit('close')"/>
          </div>
        </InlineStack>
      </template>

      <Box style="padding: 16px">
        <BlockStack gap="400">
          <InlineStack align="space-between" blockAlign="center" v-if="project.active_assignment">
            <UserBox role :user="project.active_assignment.expert" />

            <Badge tone="success" size="large">Completed</Badge>
          </InlineStack>

          <Text variant="bodyMd" as="p">
            The expert has marked this project as completed. If you agree that all project requirements have been met, please confirm that the project is completed. After this action, the project will change status to 'Completed'.
          </Text>

          <Divider />

          <BlockStack gap="200">

            <Select
                label="What is the value range of this project?"
                name="project_value_range"
                :options="valueRangeOptions"
                v-model="selectedValueRange"
                placeholder="Select value range of this project ..."
            />

            <BlockStack gap="600">
              <BlockStack gap="400">
                <StarRating
                    label="Quality of service"
                    :rating="review.quality"
                    @update:rating="val => review.quality = val"
                />
                <StarRating
                    label="Communication"
                    :rating="review.communication"
                    @update:rating="val => review.communication = val"
                />
                <StarRating
                    label="Turnaround Time"
                    :rating="review.timeToStart"
                    @update:rating="val => review.timeToStart = val"
                />
                <StarRating
                    label="Value for money"
                    :rating="review.valueForMoney"
                    @update:rating="val => review.valueForMoney = val"
                />

                <Text variant="headingLg">Total Score: {{ totalScore }}</Text>
              </BlockStack>

              <TextField
                  label="Share your overall experience with this expert"
                  autoComplete="off"
                  v-model="review.comment"
                  :multiline="5"
                  helpText="This feedback is public and will be shared on the expert's profile."
                  placeholder="Please try to be as detailed as possible. Share your experience with this expert and help us make your next match even better ..."
                  :error="null" />

              <Select
                  label="How likely are you to recommend this expert to others?"
                  name="recommendation_likelihood"
                  :options="recommendationOptions"
                  v-model="review.recommendation"
                  placeholder="Select likelihood ..."
              />
            </BlockStack>
          </BlockStack>

        </BlockStack>
      </Box>

      <template #footer>
        <InlineStack align="end" gap="200">
          <Button @click="() => this.$emit('close')">Cancel</Button>

          <InputBtn :icon="CheckCircle" @click="markFinished"
                    :disabled="!review.comment || review.comment.length < 5 ||
             review.timeToStart === 0 || review.quality === 0 ||
             review.communication === 0 || review.valueForMoney === 0 ||
             review.recommendation === null || selectedValueRange === null">Mark as Completed</InputBtn>
        </InlineStack>
      </template>
    </MobileModal>
  </template>
  <template v-else>
    <div style="position: fixed; overflow-y: auto; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; background: #00000033"
         @click="() => this.$emit('close')">
      <BlockStack inlineAlign="center" align="center">
        <Card style="width: 620px; overflow-y: auto;" :padding="null" @click.stop="null">
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
                Mark this project as completed
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
              <InlineStack align="space-between" blockAlign="center" v-if="project.active_assignment">
                <UserBox role :user="project.active_assignment.expert" />

                <Badge tone="success" size="large">Completed</Badge>
              </InlineStack>

              <Text variant="bodyMd" as="p">
                The expert has marked this project as completed. If you agree that all project requirements have been met, please confirm that the project is completed. After this action, the project will change status to 'Completed'.
              </Text>

              <Divider />

              <Select
                  label="What is the value range of this project?"
                  name="project_value_range"
                  :options="valueRangeOptions"
                  v-model="selectedValueRange"
                  placeholder="Select value range of this project ..."
              />

              <BlockStack gap="600">
                <BlockStack gap="400">
                  <StarRating
                      label="Quality of service"
                      :rating="review.quality"
                      @update:rating="val => review.quality = val"
                  />
                  <StarRating
                      label="Communication"
                      :rating="review.communication"
                      @update:rating="val => review.communication = val"
                  />
                  <StarRating
                      label="Turnaround Time"
                      :rating="review.timeToStart"
                      @update:rating="val => review.timeToStart = val"
                  />
                  <StarRating
                      label="Value for money"
                      :rating="review.valueForMoney"
                      @update:rating="val => review.valueForMoney = val"
                  />

                  <Text variant="headingLg">Total Score: {{ totalScore }}</Text>
                </BlockStack>

                <TextField
                    label="Share your overall experience with this expert"
                    autoComplete="off"
                    v-model="review.comment"
                    :multiline="5"
                    helpText="This feedback is public and will be shared on the expert's profile."
                    placeholder="Please try to be as detailed as possible. Share your experience with this expert and help us make your next match even better ..."
                    :error="null" />

                <Select
                    label="How likely are you to recommend this expert to others?"
                    name="recommendation_likelihood"
                    :options="recommendationOptions"
                    v-model="review.recommendation"
                    placeholder="Select likelihood ..."
                />
              </BlockStack>

            </BlockStack>
          </Box>

          <Box padding="400">
            <InlineStack align="end" gap="200">
              <Button @click="() => this.$emit('close')">Cancel</Button>

              <InputBtn :icon="CheckCircle" @click="markFinished"
                        :disabled="!review.comment || review.comment.length < 5 ||
             review.timeToStart === 0 || review.quality === 0 ||
             review.communication === 0 || review.valueForMoney === 0 ||
             review.recommendation === null || selectedValueRange === null">Mark as Completed</InputBtn>
            </InlineStack>
          </Box>
        </Card>
      </BlockStack>
    </div>
  </template>
</template>

<style scoped>

</style>