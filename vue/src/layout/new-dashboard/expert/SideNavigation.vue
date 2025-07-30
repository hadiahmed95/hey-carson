<script>
export default {
  name: "SideNavigation",
  props: {
    active: {
      type: String,
      default: 'Overview'
    }
  },
  data() {
    return {
      menuItems: [
        { label: 'Overview', icon: '', address: '/expert/new-dashboard' },
        { label: 'Leads', icon: '', address: '/expert/leads' },
        { label: 'My Listing', icon: '', address: '/expert/new-dashboard' },
        { label: 'My Projects', icon: '', address: '/expert/new-dashboard' },
        { label: 'Reviews', icon: '', address: '/expert/new-dashboard' },
      ]
    }
  },
  methods: {
    goto(address) {
      this.$router.push(address)
    }
  }
}
</script>

<template>
  <div class="sidebar">
    <nav class="nav-menu">
      <ul>
        <li @click="goto(item.address)" v-for="item in menuItems" :key="item.label" :class="{ active: this.active === item.label }">
          <span class="icon">{{ item.icon }}</span>
          <span class="label">{{ item.label }}</span>
        </li>
      </ul>
    </nav>

    <div class="account-info">
      <p class="title">Account type:</p>
      <p class="value">Freelancer</p>

      <p class="title">Current plan:</p>
      <p class="value">Premium</p>

      <p class="title">Listed since:</p>
      <p class="value">17 Dec,2025</p>

      <button class="upgrade-button">Upgrade Plan</button>
    </div>
  </div>
</template>

<style scoped>
.sidebar {
  width: 220px;
  background: #fff;
  padding: 35px 25px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100vh;
  border-right: 1px solid #eee;
}

.nav-menu ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-menu li {
  display: flex;
  align-items: center;
  padding: 8px 5px;
  border-radius: 5px;
  cursor: pointer;
  margin-bottom: 8px;
  font-size: 15px;
  font-weight: 500;
  color: #1c1c1c;
}

.nav-menu li.active {
  background-color: #f2f2f2;
  border: 1px solid #e2e2e2;
  font-weight: bold;
}

.nav-menu .icon {
  margin-right: 10px;
  font-size: 18px;
}

.account-info {
  position: fixed;
  bottom: 120px;
  background: #1c1c1c;
  color: white;
  padding: 16px;
  border-radius: 5px;
  font-size: 14px;
}

.account-info .title {
  opacity: 0.5;
  font-size: 12px;
}

.account-info .value {
  margin-bottom: 10px;
  font-weight: 500;
}

.upgrade-button {
  background: white;
  color: black;
  border: none;
  border-radius: 4px;
  padding: 5px 20px;
  cursor: pointer;
  font-weight: bold;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
  width: 100%;
}
</style>