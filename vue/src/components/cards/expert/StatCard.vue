<template>
  <div class="stat-card">
    <div class="stat-header">
      <span class="stat-label">{{ title }}</span>
      <span class="info-icon">i</span>
    </div>
    <div class="stat-value">
      {{ value }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatCard',
  props: {
    title: {
      type: String,
      default: 'Leads'
    },
    value: {
      type: [String, Number],
      default: 0
    }
  }
}
</script>

<style scoped>
.stat-card {
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 20px 15px;
  background: white;
  max-width: 370px;
  box-sizing: border-box;
}

.stat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.stat-label {
  font-size: 16px;
  font-weight: 500;
  color: #1c1c1c;
}

.info-icon {
  background-color: #1c1c1c;
  color: white;
  border-radius: 50%;
  width: 15px;
  height: 15px;
  font-size: 10px;
  text-align: center;
  line-height: 15px;
  font-weight: bold;
  cursor: default;
}



.stat-value {
  font-size: 32px;
  color: #1c1c1c;
  margin-top: 20px;
}
</style>
