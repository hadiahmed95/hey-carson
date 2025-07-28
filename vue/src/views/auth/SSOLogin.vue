<template>
  <div>Logging you in...</div>
</template>

<script>
import axios from "axios";
import socket from "@/mixins/socket";

export default {
  async mounted() {
    const token = this.$route.query.token;

    if (!token) {
      this.$router.push('/client/login');
      return;
    }

    try {
      localStorage.setItem('CURRENT_TOKEN', token);
      socket.methods.initializeSocket(token);
      const userResponse = await axios.get('/api/auth-check', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      const user = userResponse.data.user;
      localStorage.setItem('CURRENT_USER', JSON.stringify(user));
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

      if (user.role_id === 2) {
        this.$router.push('/client');
      } else if (user.role_id === 3) {
        this.$router.push('/expert');
      }
    } catch (e) {
      console.error('Login failed', e);
      this.$router.push('/client/login');
    }
  },
};
</script>
