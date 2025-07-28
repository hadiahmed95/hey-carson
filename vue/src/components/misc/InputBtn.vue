<script>
import LoaderIcon from "@/components/icons/LoaderIcon.vue";

export default {
  name: "InputBtn",

  props: {
    type: {
      default: '',
      type: String,
    },
    size: {
      default: 'medium',
      type: String
    },
    icon: {
      default: null,
    },
    loading: {
      default: false,
      type: Boolean
    },
    disabled: {
      default: false,
      type: Boolean
    },
    plain: {
      default: false,
      type: Boolean
    }
  },

  data() {
    return {
      LoaderIcon
    }
  },
}
</script>

<template>
  <Box v-if="!plain" :class="loading ? 'btn-loading' : disabled ? 'btn-disable' : 'btn-primary'" borderRadius="200">
    <Box padding="150">
      <InlineStack :gap="null" :wrap="false" blockAlign="center" align="center" :style="size === 'medium' ? 'margin: -2px 0!important;' : null">
        <div class="icon" v-if="icon">
          <Icon :source="icon"/>
        </div>
        <Box paddingInlineStart="050" paddingInlineEnd="150">
          <Text fontWeight="semibold" variant="bodyMd"><slot></slot></Text>
        </Box>
        <div v-if="loading" class="btn-spinner">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg>
        </div>
      </InlineStack>
    </Box>
  </Box>
  <Box v-else :class="loading ? 'btn-plain-loading' : disabled ? 'btn-plain-disable' : 'btn-plain'" borderRadius="200">
    <Box padding="150">
      <InlineStack :gap="null" :wrap="false" blockAlign="center" align="center" :style="size === 'medium' ? 'margin: -2px 0!important;' : null">
        <div class="icon" v-if="icon">
          <Icon :source="icon"/>
        </div>
        <Box paddingInlineStart="050" paddingInlineEnd="150">
          <Text fontWeight="semibold" variant="bodyMd"><slot></slot></Text>
        </Box>
        <div v-if="loading" class="btn-spinner">
          <LoaderIcon/>
        </div>
      </InlineStack>
    </Box>
  </Box>
</template>

<style scoped>
.btn-primary {
  cursor: pointer;
  background: #1F2125 !important;
  color: #FFFFFF!important;
}
.btn-primary .icon {
  color: #ACE46F !important;
}
.btn-disable .icon {
  color: #e2f3c9 !important;
}
.btn-primary:hover .icon {
  color: #1F2125 !important;
}
.btn-primary:active .icon {
  color: #1F2125 !important;
}
.btn-primary:hover {
  cursor: pointer;
  background: #ACE46F !important;
  color: #1F2125 !important;
}
.btn-primary:active {
  background: #e2f3c9 !important;
  color: #1F2125 !important;
}

.btn-primary.Polaris-Button__Icon.Polaris-Icon.Polaris-Icon__Svg {
  fill: #262A46!important;
}

.btn-loading {
  cursor: default;
  background: #1F2125 !important;
  color: #1F2125 !important;
}
.btn-disable {
  cursor: default;
  color: #FFFFFF !important;
  background: #a5a6a7 !important;
}
.btn-spinner {
  position: absolute;
  max-height: 20px;
}
.btn-spinner svg {
  fill: rgb(204, 204, 204) !important;
  animation: var(--p-motion-keyframes-spin) var(--p-motion-duration-500) linear infinite;
  width: 20px;
  height: 20px;
}

.btn-plain {
  cursor: pointer;
  border: 1px solid #E3E3E3;
  color: #1F2125;
}
.btn-plain:hover {
  background: #FAFAFA;
}
.btn-plain:active {
  background: #FAFAFAAA;
}
.btn-plain-disable {
  cursor: pointer;
  background: #f7f7f7;
  border: 1px solid #f1f1f1;
  color: #a4a4a4 !important;
}

.btn-plain-loading {
  cursor: pointer;
  background: #fafafa;
  border: 1px solid #e3e3e3;
  color: #fafafa !important;
}
</style>