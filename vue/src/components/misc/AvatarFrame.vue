<script>
export default {
  name: "AvatarFrame",

  props: {
    user: {
      default: () => {},
      type: Object
    },

    rounded: {
      default: false,
      type: Boolean
    },

    size: {
      default: 'md',
      type: String
    }
  },

  computed: {
    imgSize() {
      let classes = '';

      this.rounded ? classes += 'rounded ' : classes += 'standard '

      classes += this.avatarSize

      return classes
    },

    avatarSize() {
      let size = '';

      if (this.size === 'xl') {
        size += 'xl'
      }
      if (this.size === 'lg') {
        size += 'lg'
      }
      if (this.size === 'md') {
        size += 'md'
      }

      return size
    }
  },

  methods: {
    getPhoto() {
      return process.env.VUE_APP_AWS_LINK + this.user.photo
    }
  }
}
</script>

<template>
  <div v-if="user && user.photo" style="display: flex; align-items: center">
    <img :class="imgSize"
         :src="getPhoto()" alt="">
  </div>
  <Avatar v-else customer :size="size" :class="imgSize" />
</template>

<style scoped>
.rounded {
  border-radius: 100%;
}

.standard {
  border-radius: 6px;
}

.xl {
  width: 88px;
  height: 88px
}

.lg {
  width: 40px;
  height: 40px;
}

.md {
  width: 28px;
  height: 28px;
}
</style>