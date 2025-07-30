<template>
  <TopNavigation v-infinite-scroll="{ threshold: 1, callback: loadMore }" @refresh="refreshPage" class="scrollable-container">
    <MobileDashboardPage v-if="isMobile"
                         :pageLoading="pageLoading"
                         :projects="projects"
                         @search="searchFilter"
                         @createModal="() => this.createModal = true"
                         @status="updateStatus"/>
    <div v-else>
      <div class="dashboard-container">
        <SideNavigation active="Leads" />

        <div class="main-content">
          <header class="header-margin">
            <div>
              <div class="dashboard-header">
                <h1>Your Leads</h1>
                <IconButton
                    :icon="ArrowRight"
                    icon-position="right"
                    bg-color="#1c1c1c"
                    text-color="#d4fcb5"
                >
                  Invite Leads
                </IconButton>
              </div>
              <p>Stay on top of your leads easily and manage all your leads in one place.</p>
              <p>Quickly track their progress and strategize how to turn them into clients.</p>
            </div>
          </header>

          <div class="tabs">
            <button class="tab active">All Leads</button>
            <button class="tab">Quote Requests</button>
            <button class="tab">Direct Messages</button>
          </div>

          <div class="leads-list">
            <LeadCard
                v-for="lead in leads"
                :key="lead.email"
                v-bind="lead"
            />
          </div>
        </div>
      </div>
    </div>
    <CreateProjectModal v-show="createModal" :user="user" @close="() => this.createModal = false"  @createdProject="refreshPage"/>
  </TopNavigation>
  <LoadingSpinner :isLoading="spinnerLoading" />

</template>


<script>
import LeadCard from '../../../components/cards/expert/LeadCard.vue'
import TopNavigation from "@/layout/new-dashboard/expert/TopNavigation.vue";
import SideNavigation from "@/layout/new-dashboard/expert/SideNavigation.vue";
import LoadingSpinner from "@/components/misc/LoadingSpinner.vue";
import IconButton from "@/components/misc/IconButton.vue";
import MobileDashboardPage from "@/views/expert/mobile/MobileDashboardPage.vue";
import CreateProjectModal from "@/components/modals/CreateProjectModal.vue";

export default {
  name: "DashboardPage",
  components: {
    CreateProjectModal, MobileDashboardPage, LoadingSpinner, TopNavigation, SideNavigation, LeadCard, IconButton
  },
  data() {
    return {
      selectedTab: 0,
      tabs: [
        { id: 'leads', content: 'Latest Leads' },
        { id: 'reviews', content: 'Latest Reviews' },
        { id: 'leads', content: 'Latest Leads' },
        { id: 'reviews', content: 'Latest Reviews' }
      ],
      leads: [
        {
          name: 'Michael Oswald',
          email: 'michael@supersport.com',
          avatar: 'https://i.pravatar.cc/100?img=3',
          store: 'SuperStore',
          type: 'Direct Message',
          storeUrl: '#',
          plan: 'Advanced',
          date: '17 Dec, 2025'
        },
        {
          name: 'Jessica Rustowski',
          email: 'jessica@kitty.com',
          avatar: 'https://i.pravatar.cc/100?img=5',
          store: 'KittyShop',
          storeUrl: '#',
          plan: 'Shopify',
          budget: '$2500.00',
          type: 'Quote Request',
          badge: 'Offer Sent',
          date: '17 Dec, 2025'
        },
        {
          name: 'Paul Wilkinson',
          email: 'paul@coffeeoasis.com',
          avatar: 'https://i.pravatar.cc/100?img=6',
          store: 'CoffeeOasis',
          type: 'Direct Message',
          storeUrl: '#',
          plan: 'Advanced',
          date: '17 Dec, 2025'
        },
        {
          name: 'Jack Rancher',
          email: 'jack@unitedbyblue.com',
          avatar: 'https://i.pravatar.cc/100?img=8',
          store: 'United by Blue',
          storeUrl: '#',
          plan: 'Plus',
          budget: '$3500.00',
          type: 'Quote Request',
          badge: 'Offer Sent',
          date: '18 Dec, 2025'
        }
      ]
    }
  }
}
</script>

<style scoped>

.dashboard-container {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding: 35px 24px;
  background-color: #fafafa;
}

.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-margin {
  margin-bottom: 50px;
}

.dashboard-header h1 {
  font-size: 35px;
  margin-bottom: 20px;
}

.dashboard-header p {
  margin-top: 4px;
  font-size: 16px;
}

.tabs {
  display: flex;
  gap: 20px;
  margin-bottom: 25px;
}

.tab {
  padding-bottom: 8px;
  border-bottom: 2px solid transparent;
  cursor: pointer;
  background: none;
  font-size: 16px;
}

.tab.active {
  border-color: #ace46f;
  font-weight: bold;
}

.leads-list {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

</style>