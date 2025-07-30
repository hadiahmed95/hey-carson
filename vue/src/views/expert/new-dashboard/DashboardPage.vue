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
        <SideNavigation active="Overview" />

        <div class="main-content">
          <header class="dashboard-header">
            <div>
              <h1>Welcome back, <em class="modify-font">Jonathan!</em></h1>
              <p>This is overview of your shopexperts expert dashboard.</p>
            </div>
            <select class="date-filter">
              <option>Last 30 Days</option>
              <option>Last 7 Days</option>
              <option>This Month</option>
            </select>
          </header>

          <div class="stats-grid">
            <StatCard title="Leads" value="127" />
            <StatCard title="CTA Clicks" value="35431" />
            <StatCard title="Listing page visits" value="14542" />
            <StatCard title="Unique visits" value="4392" />
          </div>

          <div class="tabs">
            <button class="tab active">Latest Lead</button>
            <button class="tab">Latest Reviews</button>
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
import StatCard from '../../../components/cards/expert/StatCard.vue'
import TopNavigation from "@/layout/new-dashboard/expert/TopNavigation.vue";
import SideNavigation from "@/layout/new-dashboard/expert/SideNavigation.vue";
import LoadingSpinner from "@/components/misc/LoadingSpinner.vue";
import MobileDashboardPage from "@/views/expert/mobile/MobileDashboardPage.vue";
import CreateProjectModal from "@/components/modals/CreateProjectModal.vue";

export default {
  name: "DashboardPage",
  components: {
    CreateProjectModal, MobileDashboardPage, LoadingSpinner, TopNavigation, SideNavigation, LeadCard, StatCard
  },
  data() {
    return {
      selectedTab: 0,
      tabs: [
        { id: 'leads', content: 'Latest Leads' },
        { id: 'reviews', content: 'Latest Reviews' }
      ],
      stats: {
        leads: 127,
        clicks: 35431,
        visits: 14542,
        uniques: 4392
      },
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
  margin-bottom: 24px;
}

.dashboard-header h1 {
  font-size: 35px;
  margin-bottom: 15px;
}

.dashboard-header p {
  margin-top: 4px;
  font-size: 16px;
}

.date-filter {
  padding: 6px 5px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 50px;
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

.modify-font {
  font-family: 'Georgia', serif;
  font-style: italic;
}
</style>