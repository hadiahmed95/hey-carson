<template>
  <div v-if="visible" class="notification" :class="type">
    <span>{{ message }}</span>
  </div>
</template>

<script>
export default {
  name: "ProjectStatusChangeAlert",
  props: {
    message: String,
    type: {
      type: String,
      default: "success"
    }
  },
  data() {
    return {
      visible: false
    };
  },
  methods: {
    showNotification() {
      this.visible = true;

      setTimeout(() => {
        this.visible = false;
      }, 2000);
    }
  },
  watch: {
    message() {
      if (this.message) this.showNotification();
    }
  }
};
</script>

<style scoped>
.notification {
  padding: 10px 30px;
  border-radius: 5px;
  position: fixed;
  top: 60px;
  right: 1px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #1F2125;
  z-index: 1000;
}

.notification.success {
  background-color: #ACE46F;
}

.notification.error {
  background-color: red;
}

@media (max-width: 1097px) {
  .notification {
    top: 100px;
  }
}

@media (max-width: 808px) {
  .notification {
    top: 140px;
  }
}

@media (max-width: 734px) {
  .notification {
    top: 180px;
  }
}

@media (max-width: 665px) {
  .notification {
    top: 220px;
  }
}

@media (max-width: 658px) {
  .notification {
    top: 260px;
  }
}

@media (max-width: 635px) {
  .notification {
    top: 300px;
  }
}

</style>
