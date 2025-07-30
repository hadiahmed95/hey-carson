<template>
  <div class="lead-card">
    <div class="lead-card__header">
      <div>
        <strong>{{ type }}</strong>
        <span v-if="badge" class="status-badge">{{ badge }}</span>
      </div>
      <span class="submitted-date">Submitted on {{ date }}</span>
    </div>
    <div class="lead-card__content">
      <div class="lead-card__info">
        <div class="lead-card__info-item">
          <img :src="avatar" alt="Avatar" class="avatar" />
          <div>
            <div class="name">{{ name }}</div>
            <div class="email">{{ email }}</div>
          </div>
        </div>
        <div class="divider" />
        <a :href="storeUrl" target="_blank" class="store-name">
          {{ store }}
        </a>
        <div class="divider" />
        <div class="plan">Shopify plan: {{ plan }}</div>
        <div v-if="budget" class="divider" />
        <div v-if="budget" class="plan">Budget: {{ budget }}</div>
      </div>

      <div class="lead-card__actions">
        <select v-model="status" class="status-select">
          <option>In Progress</option>
          <option>Completed</option>
          <option>Closed</option>
        </select>
        <button class="chat-button">▶ Chat Now</button>
        <button class="quote-button">📄 Send Quote</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LeadCard',
  props: {
    name: String,
    email: String,
    avatar: String,
    store: String,
    storeUrl: String,
    plan: String,
    budget: String,
    type: String,
    badge: String,
    date: String
  },
  data() {
    return {
      status: 'In Progress'
    }
  }
}
</script>

<style scoped>
.lead-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 12px;
  background-color: #fff;
}

.lead-card__header {
  display: flex;
  justify-content: space-between;
  font-size: 16px;
  margin-bottom: 20px;
}

.submitted-date {
  color: #888;
  font-size: 12px;
}

.lead-card__content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.lead-card__info {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 15px;
  flex: 1;
}

.lead-card__info-item {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}

.avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
}

.name {
  font-weight: 500;
  font-size: 14px;
}

.email {
  color: #666;
  font-size: 12px;
}

.divider {
  width: 1px;
  height: 32px;
  background-color: #ccc;
}

.status-badge {
  background-color: #d6eaff;
  color: #1798ff;
  font-size: 12px;
  font-weight: bold;
  padding: 2px 8px;
  border-radius: 4px;
  margin-left: 8px;
}

.store-name {
  color: #1798ff;
  text-decoration: none;
  font-weight: 500;
}

.plan {
  font-size: 14px;
  color: #222;
}

.lead-card__actions {
  display: flex;
  gap: 10px;
}

.status-select {
  width: 160px;
  max-width: 160px;
  padding: 6px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.chat-button {
  background-color: #1c1c1c;
  color: white;
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.quote-button {
  background-color: white;
  color: #1c1c1c;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
}
</style>
