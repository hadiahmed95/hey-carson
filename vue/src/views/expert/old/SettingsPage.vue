<script>
import ExpertLayout from "@/layout/ExpertLayout.vue";
import MobileCard from "@/components/MobileCard.vue";

export default {
  name: "SettingsPage",
  components: {ExpertLayout, MobileCard},

  data() {
    return {
      isMobile: screen.width <= 760,

      availableTimezones: [
        {label: '(GMT -05:00) East Coast (US & Canada)', value: 'gmt-5'},
        {label: '(GMT -06:00) Central time (US & Canada)', value: 'gmt-6'}
      ],
      selectedTimezone: "gmt-6",

      countries: [
        {label: 'Canada', value: 'ca'},
        {label: 'USA', value: 'us'}
      ],
      roles: [
        {label: 'Frontend Developer', value: 'fed'},
        {label: 'Backend Developer', value: 'bed'}
      ],
      experiences: [
        {label: '4 years', value: '4'},
        {label: '5 years', value: '5'},
        {label: '6 years', value: '5'}
      ],
      english: [
        {label: 'Experienced', value: '1'},
        {label: 'Fluent', value: '2'},
        {label: 'Native', value: '3'}
      ],
      availability: [
        {label: '10-20 hours per week', value: '10'},
        {label: '20-30 hours per week', value: '20'},
        {label: '30-40 hours per week', value: '30'},
        {label: '40+ hours per week', value: '40'}
      ],

      form: {
        firstName: '',
        lastName: '',
        email: '',
        country: 'ca',
        url: '',
        role: 'fed',
        experience: '5',
        engLvl: '2',
        available: '30',
        rate: '',
        info: ""
      }
    }
  }
}
</script>

<template>
  <ExpertLayout>
    <template v-if="isMobile">
      <BlockStack gap="400" style="padding: 32px 16px">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">Settings</Text>
        </InlineStack>

        <InlineStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Personal Details
            </Text>

            <Text variant="bodySm" as="p">
              add your profile image and update/manage your company details.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <InlineStack gap="400" blockAlign="center">
                <Avatar initials="JK" size="xl" />

                <div>
                  <Button>Upload Photo</Button>
                </div>
              </InlineStack>

              <Divider />

              <FormLayoutGroup>
                <TextField
                    label="First Name"
                    autoComplete="off"
                    v-model="form.firstName"
                />

                <TextField
                    label="Last Name"
                    autoComplete="off"
                    v-model="form.lastName"
                />
              </FormLayoutGroup>

              <TextField
                  type="email"
                  label="Email"
                  helpText="Your email is not visible to the experts."
                  autoComplete="off"
                  v-model="form.email"
              />

              <Select
                  label="Country of residence"
                  :options="countries"
                  v-model="form.country"
              />

              <TextField
                  label="LinkedIn/Portfolio website URL"
                  autoComplete="off"
                  v-model="form.url"
              />

              <Select
                  label="Role"
                  :options="roles"
                  v-model="form.role"
              />

              <Select
                  label="Years of experience"
                  :options="experiences"
                  v-model="form.experience"
              />

              <Select
                  label="What is your English level?"
                  :options="english"
                  v-model="form.engLvl"
              />

              <Select
                  label="What is your availability per week?"
                  helpText="Freelancers typically need to commit to at least 10 hours per week."
                  :options="availability"
                  v-model="form.available"
              />

              <TextField
                  label="Expected hourly rate"
                  autoComplete="off"
                  v-model="form.rate"
              />

              <TextField
                  label="Professional bio"
                  autoComplete="off"
                  :multiline="5"
                  v-model="form.info"
              />
            </BlockStack>
          </MobileCard>
        </InlineStack>

        <Divider borderColor="border" />

        <BlockStack gap="300"
        >
          <BlockStack>
            <Text variant="headingSm" as="p">
              Payout Options
            </Text>

            <Text variant="bodySm" as="p">
              WIP
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <Text>WIP</Text>

              <InlineStack>
                <Button>WIP</Button>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <Divider borderColor="border" />

        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Timezone
            </Text>

            <Text variant="bodySm" as="p">
              Set your timezone to facilitate smoother communication with experts on projects.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <Select
                  label="Your timezone"
                  :options="availableTimezones"
                  v-model="selectedTimezone"
              />
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <Divider borderColor="border" />

        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Email Preferences
            </Text>

            <Text variant="bodySm" as="p">
              Customize notifications to fit your workflow. Get instant updates or daily summaries for crucial project information.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <InlineStack align="space-between">
                <BlockStack gap="100">
                  <Text variant="bodyMd">Project Notifications</Text>
                  <Text variant="bodyXs" tone="subdued">Instant Notifications</Text>
                </BlockStack>

                <ButtonGroup>
                  <Button variant="primary">Instant</Button>
                  <Button>Daily Summary</Button>
                </ButtonGroup>
              </InlineStack>

              <InlineStack align="space-between">
                <BlockStack gap="100">
                  <Text variant="bodyMd">New Messages</Text>
                  <Text variant="bodyXs" tone="subdued">Daily Summary</Text>
                </BlockStack>
                <ButtonGroup>
                  <Button>Instant</Button>
                  <Button variant="primary">Daily Summary</Button>
                </ButtonGroup>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <Divider borderColor="border" />

        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Password
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <Text>You have never changed your password.</Text>

              <InlineStack>
                <Button>Change your password</Button>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>

        <Divider borderColor="border" />

        <BlockStack gap="300">
          <BlockStack>
            <Text variant="headingSm" as="p">
              Close Account
            </Text>

            <Text variant="bodySm" as="p">
              By closing your account, you will be logged off all your devices, and all the information associated with your account will be deleted.
            </Text>
          </BlockStack>

          <MobileCard padding="500">
            <BlockStack gap="500">
              <Text>We're sorry to see you go. Our team will receive your request and proceed to close your account.</Text>

              <InlineStack>
                <Button tone="critical">Send Close Request</Button>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>

    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 48px"
            title="Settings"
            :backAction="{ content: 'Dashboard', onAction: () => {this.$router.push('/expert')} }"
      >
        <BlockStack gap="600">
          <LayoutAnnotatedSection
              id="personalDetails"
              title="Personal Details"
              description="add your profile image and update/manage your company details."
          >
            <Card padding="500">
              <BlockStack gap="500">
                <InlineStack gap="400" blockAlign="center">
                  <Avatar initials="JK" size="xl" />

                  <div>
                    <Button>Upload Photo</Button>
                  </div>
                </InlineStack>

                <Divider />

                <FormLayoutGroup>
                  <TextField
                      label="First Name"
                      autoComplete="off"
                      v-model="form.firstName"
                  />

                  <TextField
                      label="Last Name"
                      autoComplete="off"
                      v-model="form.lastName"
                  />
                </FormLayoutGroup>

                <TextField
                    type="email"
                    label="Email"
                    helpText="Your email is not visible to the experts."
                    autoComplete="off"
                    v-model="form.email"
                />

                <Select
                    label="Country of residence"
                    :options="countries"
                    v-model="form.country"
                />

                <TextField
                    label="LinkedIn/Portfolio website URL"
                    autoComplete="off"
                    v-model="form.url"
                />

                <Select
                    label="Role"
                    :options="roles"
                    v-model="form.role"
                />

                <Select
                    label="Years of experience"
                    :options="experiences"
                    v-model="form.experience"
                />

                <Select
                    label="What is your English level?"
                    :options="english"
                    v-model="form.engLvl"
                />

                <Select
                    label="What is your availability per week?"
                    helpText="Freelancers typically need to commit to at least 10 hours per week."
                    :options="availability"
                    v-model="form.available"
                />

                <TextField
                    label="Expected hourly rate"
                    autoComplete="off"
                    v-model="form.rate"
                />

                <TextField
                    label="Professional bio"
                    autoComplete="off"
                    :multiline="5"
                    v-model="form.info"
                />
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="payout"
              title="Payout Options"
              description="WIP"
          >
            <Card padding="500">
              <BlockStack gap="500">
                <Text>WIP</Text>

                <InlineStack>
                  <Button>WIP</Button>
                </InlineStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="timezone"
              title="Timezone"
              description="Set your timezone to facilitate smoother communication with experts on projects."
          >
            <Card padding="500">
              <BlockStack gap="500">
                <Select
                    label="Your timezone"
                    :options="availableTimezones"
                    v-model="selectedTimezone"
                />
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="email"
              title="Email Preferences"
              description="Customize notifications to fit your workflow. Get instant updates or daily summaries for crucial project information."
          >
            <Card padding="500">
              <BlockStack gap="500">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text variant="bodyMd">Project Notifications</Text>
                    <Text variant="bodyXs" tone="subdued">Instant Notifications</Text>
                  </BlockStack>

                  <ButtonGroup>
                    <Button variant="primary">Instant</Button>
                    <Button>Daily Summary</Button>
                  </ButtonGroup>
                </InlineStack>

                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text variant="bodyMd">New Messages</Text>
                    <Text variant="bodyXs" tone="subdued">Daily Summary</Text>
                  </BlockStack>
                  <ButtonGroup>
                    <Button>Instant</Button>
                    <Button variant="primary">Daily Summary</Button>
                  </ButtonGroup>
                </InlineStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="password"
              title="Password"
          >
            <Card padding="500">
              <BlockStack gap="500">
                <Text>You have never changed your password.</Text>

                <InlineStack>
                  <Button>Change your password</Button>
                </InlineStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>

          <Divider borderColor="border" />

          <LayoutAnnotatedSection
              id="closeAccount"
              title="Close Account "
              description="By closing your account, you will be logged off all your devices, and all the information associated with your account will be deleted."
          >
            <Card padding="500">
              <BlockStack gap="500">
                <Text>We're sorry to see you go. Our team will receive your request and proceed to close your account.</Text>

                <InlineStack>
                  <Button tone="critical">Send Close Request</Button>
                </InlineStack>
              </BlockStack>
            </Card>
          </LayoutAnnotatedSection>
        </BlockStack>
      </Page>
    </template>
  </ExpertLayout>
</template>

<style scoped>

</style>