<script>
import AdminLayout from "@/layout/AdminLayout.vue";
import ProjectsCard from "@/components/cards/admin/ProjectsCard.vue";
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import LoadingPage from "@/components/cards/LoadingPage.vue";
import axios from "axios";
import moment from "moment";
import AvatarFrame from "@/components/misc/AvatarFrame.vue";
import TransactionCard from "@/components/cards/admin/TransactionCard.vue";

export default {
  name: "ClientProfilePage",

  components: {
    LoadingPage,
    ProjectsCard,
    AdminLayout,
    AvatarFrame,
    TransactionCard,
  },

  async mounted() {
    await this.getClient(this.$route.params.id)
  },

  // eslint-disable-next-line
  beforeRouteUpdate(to, from) {
    this.getClient(to.param.id);
  },

  computed: {
    // totalReview() {
    //   if (this.expert.reviews) {
    //     return this.expert.reviews.length
    //   } else {
    //     return 0
    //   }
    // },
    // expertRating() {
    //   let rating = 0;
    //
    //   if (this.totalReview) {
    //     this.expert.reviews.forEach(rev => {
    //       rating += rev.rate;
    //     })
    //
    //     return (rating / this.totalReview).toFixed(1)
    //   } else {
    //     return rating.toFixed(1)
    //   }
    // },

    projects() {
      if (this.totalProjects) {
        let projects = [...this.client.projects];
        return projects.sort(function(a, b) {
          let aT = moment(a.updated_at, "YYYY-MM-DDTHH:mm:ss.SSSSZ")
          let bT = moment(b.updated_at, "YYYY-MM-DDTHH:mm:ss.SSSSZ")
          if (bT.isBefore(aT)) {
            return 1
          } else if (aT.isBefore(bT)) {
            return -1
          } else {
            0
          }
        });
      } else {
        return [];
      }
    },

    totalProjects() {
      if (this.client.projects) {
        return this.client.projects.length
      } else {
        return 0
      }
    },
    completedProjects() {
      if (this.totalProjects) {
        return this.client.projects.filter(project => project.status === 'completed').length
      } else {
        return 0
      }
    }
  },

  data() {
    return {
      StarFullIcon,

      pageLoading: true,

      activeTab: 0,
      transactions: [],
      tabs: [
        {
          id: 'projects',
          content: 'Projects',
        },
        {
          id: 'transactions',
          content: 'Transactions',
        },
        {
          id: 'prepaid-hours',
          content: 'Prepaid Hours',
        },
      ],
      balance: 0,

      client: {},
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },

    async getClient(id) {
      this.pageLoading = true;
      await axios.get('api/admin/clients/' + id).then(res => {
        this.client = res.data.client;
        this.balance = res.data.balance;
        this.transactions = res.data.transactions;

        this.pageLoading = false;
      }).catch(() => {
        this.$router.push('/admin/clients')
      });
    },

    formatDate(date) {
      return  moment(date, "YYYY-MM-DDTHH:mm:ss.SSSSZ").format('MMMM Do YYYY')
    },
  }
}
</script>

<template>
  <AdminLayout>
    <LoadingPage v-if="pageLoading" />

    <Page v-else style="padding-top: 56px; padding-bottom: 56px"
          title="Client Profile"
    >
      <BlockStack gap="800">
        <Card padding="800">
          <InlineStack align="space-between">
            <InlineStack gap="400">
              <AvatarFrame rounded size="xl" :user="client" />

              <BlockStack gap="300">
                <BlockStack gap="100">
                  <Text as="h2" variant="headingLg">
                    {{ client.first_name }}
                    {{ client.last_name }}

<!--                    <Badge size="large">Time: 08:25am</Badge>-->
                  </Text>
                  <Text as="p" variant="bodySm" style="color: #005BD3;">
                    <Link :url="client.url.startsWith('https://') ? client.url : 'https://' + client.url" target="_blank" removeUnderline>
                      {{ client.url }}
                    </Link>
                  </Text>
                  <Text as="p" variant="bodySm" style="color: #005BD3;">
                    {{ client.email }}
                  </Text>
                  <Text variant="bodySm" tone="subdued">Shopify Plan:<Text v-if="client.shopify_plan" as="span" variant="bodySm" fontWeight="semibold">{{ client.shopify_plan }}</Text></Text>
                </BlockStack>

<!--                <InlineStack align="start" blockAlign="center" gap="100">-->
<!--                  <div>-->
<!--                    <Icon :source="StarFullIcon" />-->
<!--                  </div>-->
<!--                  <Text as="p" variant="headingLg">0.0</Text>-->
<!--                  <Text as="p" variant="bodyMd" tone="subdued">(0 Reviews)</Text>-->
<!--                </InlineStack>-->

<!--                <InlineStack align="start" gap="200">-->
<!--                  <Button>Login As</Button>-->
<!--                  <Button disclosure="down">Actions</Button>-->
<!--                </InlineStack>-->
              </BlockStack>
            </InlineStack>

            <BlockStack gap="200">
              <Text as="p" variant="bodyMd" tone="subdued">
                Balance:

                <Text as="span" style="color: #303030">{{balance}} Prepaid Hours</Text>
              </Text>

              <Text as="p" variant="bodyMd" tone="subdued">
                Submitted Projects:

                <Text as="span" style="color: #303030">{{ totalProjects }}</Text>
              </Text>

              <Text as="p" variant="bodyMd" tone="subdued">
                Completed Projects:

                <Text as="span" style="color: #303030">{{ completedProjects }}</Text>
              </Text>

              <Text as="p" variant="bodyMd" tone="subdued">
                Total Payments:

                <Text as="span" style="color: #303030">$0.00</Text>
              </Text>

              <Text as="p" variant="bodyMd" tone="subdued">
                Joined Date:

                <Text as="span" style="color: #303030">{{ formatDate(client.created_at) }}</Text>
              </Text>
            </BlockStack>
          </InlineStack>
        </Card>

        <BlockStack gap="200">
          <Text as="p" variant="headingMd">Last Updates</Text>

          <Tabs style="padding: 0"
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />
        </BlockStack>

        <BlockStack v-if="this.activeTab === 0" gap="300">
          <ProjectsCard v-for="project in projects"
                        :key="project.id"
                        :project="project"/>
        </BlockStack>
        <BlockStack gap="200" v-if=" this.activeTab === 1 && this.transactions?.length">
          <TransactionCard v-for="transaction in transactions" :key="transaction.id" :item="transaction" />
        </BlockStack>
      </BlockStack>
    </Page>
  </AdminLayout>
</template>

<style scoped>

</style>