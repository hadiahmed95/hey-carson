<script>
export default {
  methods: {
    handleClick() {
      this.$emit('clicked');
    }
  }
}
</script>
<template>
  <small class="message_alert" @click="handleClick">
    <svg width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M10.707 17.707l5-5a.999.999 0 10-1.414-1.414L11 14.586V3a1 1 0 10-2 0v11.586l-3.293-3.293a.999.999 0 10-1.414 1.414l5 5a.999.999 0 001.414 0z" fill="#34C759"/>
    </svg>
    <span>New Message</span>
  </small>
</template>

<style scoped>
.message_alert {
  color: white;
  cursor: pointer;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  background: #303030;
  position: absolute;
  top: 20%;
  left: 45%;
  border-radius: 50px;
  width: auto;
  padding: 8px 16px;
}

@media (max-width: 768px) {
  .message_alert {
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    width: auto;
    padding: 8px 16px;
    font-size: 14px;
  }
}

</style>