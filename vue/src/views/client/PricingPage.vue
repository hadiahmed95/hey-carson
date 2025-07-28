<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import BuyModal from "@/components/modals/BuyModal.vue";
import InputBtn from "@/components/misc/InputBtn.vue";
import CreateProjectModal from "@/components/modals/CreateProjectModal.vue";

export default {
  name: "PricingPage",
  components: {InputBtn, CreateProjectModal, ClientLayout, BuyModal},

  data() {
    return {
      isMobile: screen.width <= 760,

      showBuyModal: false,
      showCreateModal: false,

      quota: {
        hours: 25,
        rate: 80
      },
      client:JSON.parse(window.localStorage.getItem('CURRENT_USER')),
      TEST_CLIENT_ID: 4181
    }
  },

  methods: {
    toggleBuyModal(data) {
      if (this.client?.id === this.TEST_CLIENT_ID) {
        data.rate = 0.04;
      }
      this.quota = data;

      this.showBuyModal = true;
    },

    toProjects() {
      this.$router.push('/client')
    }
  }
}
</script>

<template>
  <ClientLayout>
    <template v-if="isMobile">
      <Page style="padding: 32px 0">
        <Box paddingBlock="800" paddingInline="400">
          <BlockStack gap="600">
            <BlockStack gap="200" align="center">
              <Text variant="bodyXs" tone="subdued" alignment="center">
                SUPPORT AND MAINTENANCE PLANS FOR SHOPIFY AND SHOPIFY PLUS
              </Text>

              <Text variant="heading2xl" alignment="center">
                Simple, Transparent Pricing
              </Text>

              <Text variant="bodylg" alignment="center">
                Consistent and predictable pricing across our entire freelancer network. Get a project rate or buy pre-paid hours and save.            </Text>
            </BlockStack>


            <Box background="bg-surface"
                 borderWidth="025"
                 borderColor="border"
                 borderRadius="300"
                 padding="600">
              <BlockStack gap="800">
                <BlockStack gap="400">
                  <BlockStack gap="100">
                    <Text variant="headingLg">25 hour pack</Text>
                    <Text variant="bodyMd" tone="subdued">$2250 (one time charge) </Text>
                  </BlockStack>
                  <Divider />
                </BlockStack>

                <BlockStack gap="400">
                  <InlineStack align="start" blockAlign="center" gap="100">
                    <Text variant="heading3xl">{{ client?.id === this.TEST_CLIENT_ID ? '$0.04' : '$90' }}</Text>
                    <Text variant="bodyMd" tone="subdued">/ hour</Text>
                  </InlineStack>
                  <Text variant="bodyMd">
                    Best for brands with their own designer, and a consistent need for on-demand Shopify dev help.
                  </Text>
                </BlockStack>

                <InputBtn @click="() => this.toggleBuyModal({hours: 25, rate: 90})">Buy this pack</InputBtn>

                <BlockStack gap="300">
                  <Text variant="bodyMd">What’s included</Text>

                  <BlockStack gap="200">
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">25-hours credit balance</Text>
                    </InlineStack>
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">Development and design work</Text>
                    </InlineStack>
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">Fast turnaround</Text>
                    </InlineStack>
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">Dedicated experts</Text>
                    </InlineStack>
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">Replenish as needed</Text>
                    </InlineStack>
                  </BlockStack>
                </BlockStack>
              </BlockStack>
            </Box>

            <Box background="bg-surface"
                 borderWidth="025"
                 borderColor="border"
                 borderRadius="300"
                 padding="600">
              <BlockStack gap="800">
                <BlockStack gap="400">
                  <BlockStack gap="100">
                    <Text variant="headingLg">50 hour pack</Text>
                    <Text variant="bodyMd" tone="subdued">$4250 (one time charge) </Text>
                  </BlockStack>
                  <Divider />
                </BlockStack>

                <BlockStack gap="400">
                  <InlineStack align="start" blockAlign="center" gap="100">
                    <Text variant="heading3xl">{{ client?.id === this.TEST_CLIENT_ID ? '$0.04' : '$85' }}</Text>
                    <Text variant="bodyMd" tone="subdued">/ hour</Text>
                  </InlineStack>
                  <Text variant="bodyMd">
                    Best for brands, freelancers or agencies with consistent needs for on-demand Shopify design and dev help.
                  </Text>
                </BlockStack>

                <InputBtn @click="() => this.toggleBuyModal({hours: 50, rate: 85})">Buy this pack</InputBtn>

                <BlockStack gap="300">
                  <Text variant="bodyMd">What’s included</Text>

                  <BlockStack gap="200">
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">50-hours credit balance</Text>
                    </InlineStack>
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">Development and design work</Text>
                    </InlineStack>
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">Fast turnaround</Text>
                    </InlineStack>
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">Dedicated experts</Text>
                    </InlineStack>
                    <InlineStack gap="200">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                      </svg>

                      <Text variant="bodyMd">Replenish as needed</Text>
                    </InlineStack>
                  </BlockStack>
                </BlockStack>
              </BlockStack>
            </Box>

            <Box background="bg-surface"
                   borderWidth="025"
                   borderColor="border"
                   borderRadius="300"
                   padding="600">
                <BlockStack gap="800">
                  <BlockStack gap="400">
                    <BlockStack gap="100">
                      <Text variant="headingLg">75 hour pack</Text>
                      <Text variant="bodyMd" tone="subdued">$6000 (one time charge) </Text>
                    </BlockStack>
                    <Divider />
                  </BlockStack>

                  <BlockStack gap="400">
                    <InlineStack align="start" blockAlign="center" gap="100">
                      <Text variant="heading3xl">{{ client?.id === this.TEST_CLIENT_ID ? '$0.04' : '$80' }}</Text>
                      <Text variant="bodyMd" tone="subdued">/ hour</Text>
                    </InlineStack>
                    <Text variant="bodyMd">
                      Best for brands or agencies with consistent needs for on-demand Shopify design and dev help, across 2 or more storefronts.
                    </Text>
                  </BlockStack>

                  <InputBtn @click="() => this.toggleBuyModal({hours: 75, rate: 80})">Buy this pack</InputBtn>

                  <BlockStack gap="300">
                    <Text variant="bodyMd">What’s included</Text>

                    <BlockStack gap="200">
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">75-hours credit balance</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Development and design work</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Fast turnaround</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Dedicated experts</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Replenish as needed</Text>
                      </InlineStack>
                    </BlockStack>
                  </BlockStack>
                </BlockStack>
              </Box>
          </BlockStack>
        </Box>
      </Page>
    </template>
    <template v-else>
      <Page>
        <Box padding="1200">
          <BlockStack gap="800">
            <BlockStack gap="400">
              <Text variant="bodyXs" tone="subdued">
                SUPPORT AND MAINTENANCE PLANS FOR SHOPIFY AND SHOPIFY PLUS
              </Text>

              <Text variant="heading2xl">
                Save up with Pre-Paid Hours
              </Text>

              <Text variant="bodyLg">
                 Buy discounted pre-paid packs of 25 hours, 50 hours, and 75 hours from $80/hour.<br/>
                <Text fontWeight="semibold" as="span">
                  These are treated as express hours and can be used on multiple projects, with any freelancer in our network
                </Text>
              </Text>

              <InlineGrid :columns="3" gap="800">
                <Box background="bg-surface"
                     borderWidth="025"
                     borderColor="border"
                     borderRadius="300"
                     padding="600">
                  <BlockStack gap="800">
                    <BlockStack gap="400">
                      <BlockStack gap="100">
                        <Text variant="headingLg">25 hour pack</Text>
                        <Text variant="bodyMd" tone="subdued">$2,250 (one time charge) </Text>
                      </BlockStack>
                      <Divider />
                    </BlockStack>

                    <BlockStack gap="400">
                      <InlineStack align="start" blockAlign="center" gap="100">
                        <Text variant="heading3xl">
                          {{ client?.id === this.TEST_CLIENT_ID ? '$0.04' : '$90' }}
                        </Text>
                        <Text variant="bodyMd" tone="subdued">/ hour</Text>
                      </InlineStack>
                      <Text variant="bodyMd">
                        Best for brands with their own designer, and a consistent need for on-demand Shopify dev help.
                      </Text>
                    </BlockStack>

                    <InputBtn @click="() => this.toggleBuyModal({hours: 25, rate: 90})">Buy this pack</InputBtn>
                  </BlockStack>
                </Box>

                <Box background="bg-surface"
                     borderWidth="025"
                     borderColor="border"
                     borderRadius="300"
                     padding="600">
                  <BlockStack gap="800">
                    <BlockStack gap="400">
                      <BlockStack gap="100">
                        <Text variant="headingLg">50 hour pack</Text>
                        <Text variant="bodyMd" tone="subdued">$4,250 (one time charge) </Text>
                      </BlockStack>
                      <Divider />
                    </BlockStack>

                    <BlockStack gap="400">
                      <InlineStack align="start" blockAlign="center" gap="100">
                        <Text variant="heading3xl">{{ client?.id === this.TEST_CLIENT_ID ? '$0.04' : '$85' }}</Text>
                        <Text variant="bodyMd" tone="subdued">/ hour</Text>
                      </InlineStack>
                      <Text variant="bodyMd">
                        Best for brands, freelancers or agencies with consistent needs for on-demand Shopify design and dev help.                  </Text>
                    </BlockStack>

                    <InputBtn @click="() => this.toggleBuyModal({hours: 50, rate: 85})">Buy this pack</InputBtn>
                  </BlockStack>
                </Box>

                <Box background="bg-surface"
                     borderWidth="025"
                     borderColor="border"
                     borderRadius="300"
                     padding="600">
                  <BlockStack gap="800">
                    <BlockStack gap="400">
                      <BlockStack gap="100">
                        <Text variant="headingLg">75 hour pack</Text>
                        <Text variant="bodyMd" tone="subdued">$6,000 (one time charge) </Text>
                      </BlockStack>
                      <Divider />
                    </BlockStack>

                    <BlockStack gap="400">
                      <InlineStack align="start" blockAlign="center" gap="100">
                        <Text variant="heading3xl">{{ client?.id === this.TEST_CLIENT_ID ? '$0.04' : '$80' }}</Text>
                        <Text variant="bodyMd" tone="subdued">/ hour</Text>
                      </InlineStack>
                      <Text variant="bodyMd">
                        Best for brands or agencies with consistent needs for on-demand Shopify design and dev help, across 2 or more storefronts.                  </Text>
                    </BlockStack>

                    <InputBtn @click="() => this.toggleBuyModal({hours: 75, rate: 80})">Buy this pack</InputBtn>
                  </BlockStack>
                </Box>
              </InlineGrid>

              <div style="padding-top: 20px">
                <Text variant="headingLg">
                  Simple, Transparent Pricing
                </Text>
              </div>

              <Text variant="bodylg">
                Consistent and predictable pricing across our entire freelancer network. Get a project rate or buy pre-paid hours and save.            </Text>
            </BlockStack>

            <InlineGrid :columns="2" gap="800">
              <Box background="bg-surface"
                   borderWidth="025"
                   borderColor="border"
                   borderRadius="300"
                   padding="600">
                <BlockStack gap="800">
                  <BlockStack gap="400">
                    <BlockStack gap="100">
                      <Text variant="headingLg">Standard </Text>
                    </BlockStack>
                    <Divider />
                  </BlockStack>

                  <BlockStack gap="400">
                    <InlineStack align="start" blockAlign="center" gap="100">
                      <Text variant="heading3xl">$95</Text>
                      <Text variant="bodyMd" tone="subdued">/ hour</Text>
                    </InlineStack>
                    <Text variant="bodyMd">
                      Best for small and larger project needs without a pressing deadline.
                    </Text>
                  </BlockStack>

                  <InputBtn @click="showCreateModal = true">Start a Project</InputBtn>

                  <BlockStack gap="300">
                    <Text variant="bodyMd">What’s included</Text>

                    <BlockStack gap="200">
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Priority matching</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Fast and free quotes</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Fast turnaround</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Small and large projects</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Design and development</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Shopify and Shopify Plus</Text>
                      </InlineStack>
                    </BlockStack>
                  </BlockStack>
                </BlockStack>
              </Box>

              <Box background="bg-surface"
                   borderWidth="025"
                   borderColor="border"
                   borderRadius="300"
                   padding="600">
                <BlockStack gap="800">
                  <BlockStack gap="400">
                    <BlockStack gap="100">
                      <Text variant="headingLg">Express </Text>
                    </BlockStack>
                    <Divider />
                  </BlockStack>

                  <BlockStack gap="400">
                    <InlineStack align="start" blockAlign="center" gap="100">
                      <Text variant="heading3xl">$125</Text>
                      <Text variant="bodyMd" tone="subdued">/ hour</Text>
                    </InlineStack>
                    <Text variant="bodyMd">
                      Best for small and larger project needs with a tighter deadline.
                    </Text>
                  </BlockStack>

                  <InputBtn @click="showCreateModal = true">Start a Project</InputBtn>

                  <BlockStack gap="300">
                    <Text variant="bodyMd">What’s included</Text>

                    <BlockStack gap="200">
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Priority matching</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Fast and free quotes</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Fast turnaround</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Small and large projects</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Design and development</Text>
                      </InlineStack>
                      <InlineStack gap="200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                          <path d="M13.2803 8.78033C13.5732 8.48744 13.5732 8.01256 13.2803 7.71967C12.9874 7.42678 12.5126 7.42678 12.2197 7.71967L9.25 10.6893L8.03033 9.46967C7.73744 9.17678 7.26256 9.17678 6.96967 9.46967C6.67678 9.76256 6.67678 10.2374 6.96967 10.5303L8.71967 12.2803C9.01256 12.5732 9.48744 12.5732 9.78033 12.2803L13.2803 8.78033Z" fill="#29845A"/>
                          <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 3.5C4.73122 3.5 3.5 4.73122 3.5 6.25V13.75C3.5 15.2688 4.73122 16.5 6.25 16.5H13.75C15.2688 16.5 16.5 15.2688 16.5 13.75V6.25C16.5 4.73122 15.2688 3.5 13.75 3.5H6.25ZM5 6.25C5 5.55964 5.55964 5 6.25 5H13.75C14.4404 5 15 5.55964 15 6.25V13.75C15 14.4404 14.4404 15 13.75 15H6.25C5.55964 15 5 14.4404 5 13.75V6.25Z" fill="#29845A"/>
                        </svg>

                        <Text variant="bodyMd">Shopify and Shopify Plus</Text>
                      </InlineStack>
                    </BlockStack>
                  </BlockStack>
                </BlockStack>
              </Box>
            </InlineGrid>
          </BlockStack>
        </Box>
      </Page>
    </template>

    <BuyModal v-if="showBuyModal" :quota="quota" @close="() => showBuyModal = false"/>
    <CreateProjectModal v-show="showCreateModal" @createdProject="toProjects" @close="() => this.showCreateModal = false"/>
  </ClientLayout>
</template>

<style scoped>

</style>