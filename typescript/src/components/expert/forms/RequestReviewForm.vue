<template>
  <form @submit.prevent="sendRequest" class="space-y-4">
    <!-- Client Full Name with Search -->
    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="fullName">Client Full Name</label>
      <input
          id="fullName"
          v-model="form.fullName"
          type="text"
          placeholder="Enter Client full name"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          @input="handleClientNameInput(); delete errors.client_full_name"
          @focus="handleClientNameFocus"
          @blur="handleUserInputBlur"
      />

      <!-- Styled User List -->
      <div
          v-if="showUserDropdown && users.length > 0"
          class="mt-2 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto z-10 relative"
          @mousedown.prevent
      >
        <div class="p-2 text-sm text-gray-600 bg-gray-50 border-b">
          Select existing user or continue typing...
        </div>
        <div
            v-for="user in users"
            :key="user.id"
            @click="handleUserSelect(user)"
            class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors"
            :class="{ 'bg-blue-50': selectedUser?.id === user.id }"
        >
          <!-- Avatar -->
          <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-medium text-sm mr-3">
            {{ getInitials(user.full_name) }}
          </div>

          <!-- User Info -->
          <div class="flex-1">
            <div class="font-medium text-gray-900">{{ user.full_name }}</div>
            <div class="text-sm text-gray-500">{{ user.email }}</div>
            <div v-if="user.company_name" class="text-xs text-gray-400">
              {{ user.company_name }}
            </div>
          </div>

          <!-- Selection Indicator -->
          <div
              v-if="selectedUser?.id === user.id"
              class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center"
          >
            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path
                  fill-rule="evenodd"
                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  clip-rule="evenodd"
              ></path>
            </svg>
          </div>
        </div>
      </div>

      <h5 v-if="errors.client_full_name" class="text-red-600 mt-2">
        {{ errors.client_full_name }}
      </h5>
      <p v-if="loadingUsers" class="text-blue-600 mt-1 text-sm flex items-center">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Searching users...
      </p>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="clientEmail">Client Email</label>
      <input
          id="clientEmail"
          v-model="form.clientEmail"
          type="email"
          placeholder="example@gmail.com"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          @input="delete errors.client_email"
          :readonly="isUserFieldReadonly"
          :class="{ 'bg-gray-50': isUserFieldReadonly }"
      />
      <h5 v-if="errors.client_email" class="text-red-600 mt-2">
        {{ errors.client_email }}
      </h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="companyName">Client Company Name</label>
      <input
          id="companyName"
          v-model="form.companyName"
          type="text"
          placeholder="Company Name"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          @input="delete errors.client_company_name"
          :readonly="isUserFieldReadonly"
          :class="{ 'bg-gray-50': isUserFieldReadonly }"
      />
      <h5 v-if="errors.client_company_name" class="text-red-600 mt-2">
        {{ errors.client_company_name }}
      </h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="website">Client Company Website</label>
      <input
          id="website"
          v-model="form.website"
          type="url"
          placeholder="https://companywebsite.com"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          @input="delete errors.client_company_website"
          :readonly="isUserFieldReadonly"
          :class="{ 'bg-gray-50': isUserFieldReadonly }"
      />
      <h5 v-if="errors.client_company_website" class="text-red-600 mt-2">
        {{ errors.client_company_website }}
      </h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="hiredOnShopexperts">Hired on Shopexperts</label>
      <select
          id="hiredOnShopexperts"
          v-model="form.hiredOnShopexperts"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          @change="delete errors.hired_on_shopexperts"
      >
        <option>Yes</option>
        <option>No</option>
      </select>
      <h5 v-if="errors.hired_on_shopexperts" class="text-red-600 mt-2">
        {{ errors.hired_on_shopexperts }}
      </h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="projectName">Project Name</label>
      <input
          id="projectName"
          v-model="form.projectName"
          type="text"
          placeholder="Enter project name"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          @input="handleProjectNameInput(); delete errors.project_name"
          @focus="handleProjectNameFocus"
          @blur="handleProjectInputBlur"
      />

      <div
          v-if="showProjectDropdown && projects.length > 0"
          class="mt-2 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto z-10 relative"
          @mousedown.prevent
      >
        <div class="p-2 text-sm text-gray-600 bg-gray-50 border-b">
          Select existing project or continue typing...
        </div>
        <div
            v-for="project in projects"
            :key="project.id"
            @click="handleProjectSelect(project)"
            class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors"
            :class="{ 'bg-blue-50': selectedProject?.id === project.id }"
        >
          <!-- Project Icon -->
          <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-medium text-sm mr-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
            </svg>
          </div>

          <!-- Project Info -->
          <div class="flex-1">
            <div class="font-medium text-gray-900">{{ project.name }}</div>
          </div>

          <!-- Selection Indicator -->
          <div
              v-if="selectedProject?.id === project.id"
              class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center"
          >
            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path
                  fill-rule="evenodd"
                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  clip-rule="evenodd"
              ></path>
            </svg>
          </div>
        </div>
      </div>

      <h5 v-if="errors.project_name" class="text-red-600 mt-2">
        {{ errors.project_name }}
      </h5>
      <p v-if="loadingProjects" class="text-blue-600 mt-1 text-sm flex items-center">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading projects...
      </p>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="repeatedClient">Repeated Client</label>
      <select
          id="repeatedClient"
          v-model="form.repeatedClient"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          @change="delete errors.repeated_client"
      >
        <option>Yes</option>
        <option>No</option>
      </select>
      <h5 v-if="errors.repeated_client" class="text-red-600 mt-2">
        {{ errors.repeated_client }}
      </h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="projectValue">Project Value</label>
      <select
          id="projectValue"
          v-model="form.projectValue"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph"
          @change="delete errors.project_value_range"
      >
        <option disabled value="" class="text-gray-100">Select project value range</option>
        <option>less than 100</option>
        <option>100 - 200</option>
        <option>200 - 300</option>
        <option>300 - 400</option>
        <option>greater than 400</option>
      </select>
      <h5 v-if="errors.project_value_range" class="text-red-600 mt-2">
        {{ errors.project_value_range }}
      </h5>
    </div>

    <div class="mb-5">
      <label class="block text-h4 font-light mb-1" for="message">Request a Review Message</label>
      <textarea
          id="message"
          v-model="form.message"
          rows="8"
          class="w-full border border-grey rounded-md px-4 py-2 text-paragraph text-gray-500"
          @input="delete errors.message"
      />
      <h5 v-if="errors.message" class="text-red-600 mt-2">
        {{ errors.message }}
      </h5>
    </div>

    <FormStatusMessage
        :trigger="Date.now()"
        @updateStatus="resetStatus"
        :status="generalErrorMessage"
        message="Review requested successfully."
        class="mt-2"
    />

    <button
        type="submit"
        class="w-full bg-primary text-white text-paragraph font-normal rounded-md py-3 flex items-center justify-center gap-2 hover:bg-gray-800 transition-colors"
    >
      Send Request
      <Arrow />
    </button>
  </form>
</template>

<script setup lang="ts">
import { onUnmounted, reactive, watch, computed } from "vue";
import { ref } from "vue";
import { validateEmail } from "@/utils/helpers.ts";
import { useExpertStore } from "@/store/expert.ts";
import type { IProjectName } from "@/types.ts";
import { useAuthStore } from "@/store/auth.ts";
import Arrow from "@/assets/icons/arrow.svg";
import FormStatusMessage from "@/components/common/FormStatusMessage.vue";

const expertStore = useExpertStore();
const authStore = useAuthStore();

const emit = defineEmits<{
  (e: "close"): void;
}>();

const form = reactive({
  fullName: "",
  clientEmail: "",
  companyName: "",
  website: "",
  projectName: "",
  projectId: null as number | null,
  hiredOnShopexperts: "No",
  repeatedClient: "Yes",
  projectValue: "",
  message: `Hey [Client Name],

I hope you're pleased with how [Project Name] turned out. Could you take 2-3 minutes to leave a quick review? Your feedback helps me improve and build my shopexperts profile so future clients can find me more easily.

Thank you so much for your support!`,
});

const errors = ref<Record<string, string>>({});
const projects = ref<IProjectName[]>([]);
const users = ref<any[]>([]);
const selectedUser = ref<any>(null);
const selectedProject = ref<any>(null);
const originalUserData = ref<any>(null);
const generalErrorMessage = ref("");
const loadingUsers = ref(false);
const loadingProjects = ref(false);
const userSearchTimeout = ref<NodeJS.Timeout | null>(null);
const projectSearchTimeout = ref<NodeJS.Timeout | null>(null);
const showUserDropdown = ref(false);
const showProjectDropdown = ref(false);
const isUserManuallySelected = ref(false);

// Computed properties
const isUserFieldReadonly = computed(() => {
  return isUserManuallySelected.value && selectedUser.value && !hasUserDataChanged.value;
});

const hasUserDataChanged = computed(() => {
  if (!selectedUser.value || !originalUserData.value) return false;

  return (
      form.fullName !== originalUserData.value.full_name ||
      form.clientEmail !== originalUserData.value.email ||
      form.companyName !== (originalUserData.value.company_name || '') ||
      form.website !== (originalUserData.value.website || '')
  );
});

const handleUserInputBlur = () => {
  showUserDropdown.value = false;
};

const handleProjectInputBlur = () => {
  showProjectDropdown.value = false;
};

onUnmounted(() => {
  if (userSearchTimeout.value) {
    clearTimeout(userSearchTimeout.value);
  }
  if (projectSearchTimeout.value) {
    clearTimeout(projectSearchTimeout.value);
  }
});

watch(isUserManuallySelected, (isSelected) => {
  if (isSelected && selectedUser.value && !hasUserDataChanged.value) {
    form.hiredOnShopexperts = "Yes";
  } else {
    form.hiredOnShopexperts = "No";
  }
});

watch(hasUserDataChanged, (hasChanged) => {
  if (hasChanged) {
    form.hiredOnShopexperts = "No";
  } else if (isUserManuallySelected.value && selectedUser.value) {
    form.hiredOnShopexperts = "Yes";
  }
});

watch(() => form.hiredOnShopexperts, (newValue) => {
  if (newValue === "No") {
    projects.value = [];
    selectedProject.value = null;
    showProjectDropdown.value = false;
    form.projectId = null;
  }
});

// Helper functions
const getInitials = (name: string) => {
  return name
      .split(" ")
      .map((n) => n[0])
      .join("")
      .toUpperCase()
      .slice(0, 2);
};

const clearUserFields = () => {
  form.clientEmail = "";
  form.companyName = "";
  form.website = "";
  form.projectId = null;
  form.projectName = "";
  projects.value = [];
  selectedProject.value = null;
  showProjectDropdown.value = false;
};

// Event handlers
const handleClientNameFocus = () => {
  if (form.fullName.length >= 2) {
    searchUsers(form.fullName);
  }
};

const handleClientNameInput = () => {
  if (isUserManuallySelected.value && selectedUser.value) {
    if (form.fullName !== originalUserData.value?.full_name) {
      isUserManuallySelected.value = false;
      selectedUser.value = null;
      originalUserData.value = null;
      form.hiredOnShopexperts = "No";
      clearUserFields();
    }
  } else {
    clearUserFields();
  }

  if (userSearchTimeout.value) {
    clearTimeout(userSearchTimeout.value);
  }

  if (!form.fullName || form.fullName.length < 2) {
    users.value = [];
    showUserDropdown.value = false;
    return;
  }

  userSearchTimeout.value = setTimeout(async () => {
    await searchUsers(form.fullName);
  }, 500);
};

const handleProjectNameFocus = async () => {
  if (selectedUser.value && form.hiredOnShopexperts === "Yes") {
    await loadAndFilterProjects();
  } else if (!selectedUser.value) {
    await loadAndFilterProjects();
  }
};

const handleProjectNameInput = () => {
  if (projectSearchTimeout.value) {
    clearTimeout(projectSearchTimeout.value);
  }

  if (!form.projectName || form.projectName.length < 2) {
    if (!selectedUser.value) {
      loadAndFilterProjects();
      return;
    }

    if (form.hiredOnShopexperts === "No") {
      projects.value = [];
      selectedProject.value = null;
      showProjectDropdown.value = false;
    }
    return;
  }

  projectSearchTimeout.value = setTimeout(async () => {
    await loadAndFilterProjects(form.projectName);
  }, 300);
};

const handleUserSelect = (user: any) => {
  selectedUser.value = user;
  originalUserData.value = { ...user };
  isUserManuallySelected.value = true;

  form.fullName = user.full_name;
  form.clientEmail = user.email;
  form.companyName = user.company_name || "";
  form.website = user.website || "";

  users.value = [];
  showUserDropdown.value = false;

  if (userSearchTimeout.value) {
    clearTimeout(userSearchTimeout.value);
    userSearchTimeout.value = null;
  }

  form.projectName = "";
  form.projectId = null;
  selectedProject.value = null;
  projects.value = [];
};

const handleProjectSelect = (project: any) => {
  selectedProject.value = project;
  form.projectName = project.name;
  form.projectId = project.id;

  projects.value = [];
  showProjectDropdown.value = false;

  if (projectSearchTimeout.value) {
    clearTimeout(projectSearchTimeout.value);
    projectSearchTimeout.value = null;
  }
};

const searchUsers = async (searchTerm: string) => {
  try {
    loadingUsers.value = true;
    showUserDropdown.value = true;
    const response = await expertStore.searchUsers(searchTerm);
    users.value = response.users || [];
  } catch (error) {
    console.error("Error searching users:", error);
    users.value = [];
  } finally {
    loadingUsers.value = false;
  }
};

const loadAndFilterProjects = async (searchTerm = '') => {
  // Skip if user selected but not hired on shopexperts
  if (selectedUser.value && form.hiredOnShopexperts === "No") return;

  try {
    loadingProjects.value = true;
    showProjectDropdown.value = true;

    // Pass user ID only if user is selected and hired on shopexperts
    const userId = (selectedUser.value && form.hiredOnShopexperts === "Yes")
        ? selectedUser.value.id
        : null;

    const response = await expertStore.fetchProjectNames(userId);

    if (!searchTerm.trim()) {
      projects.value = response;
    } else {
      projects.value = response.filter((project: any) =>
          project.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }
  } catch (error) {
    console.error("Error loading projects:", error);
    projects.value = [];
  } finally {
    loadingProjects.value = false;
  }
};

const validateForm = () => {
  errors.value = {};

  if (!form.fullName.trim()) {
    errors.value.client_full_name = "Client name is required.";
  }

  if (!form.clientEmail.trim()) {
    errors.value.client_email = "Client's Email is required.";
  } else if (!validateEmail(form.clientEmail.trim())) {
    errors.value.client_email = "Please enter a valid email.";
  }

  if (!form.projectName.trim()) {
    errors.value.project_name = "Project name is required.";
  }

  if (!form.message.trim() || form.message.length < 10) {
    errors.value.message = "Message must be at least 10 characters.";
  }

  return Object.keys(errors.value).length === 0;
};

const resetStatus = () => {
  if (generalErrorMessage.value === "success") {
    emit("close");
    generalErrorMessage.value = "";
  }
  generalErrorMessage.value = "";
};

// Updated sendRequest function to use the new combined endpoint
const sendRequest = async () => {
  if (!validateForm()) return;

  try {
    // Use the new combined endpoint that handles everything
    await expertStore.createReviewRequest({
      expert_id: authStore.user.id,
      client_full_name: form.fullName,
      client_email: form.clientEmail,
      client_company_name: form.companyName,
      client_company_website: form.website,
      project_id: form.projectId, // Can be null for new projects
      project_name: form.projectName,
      hired_on_shopexperts: form.hiredOnShopexperts === "Yes",
      repeated_client: form.repeatedClient === "Yes",
      is_client_reviewed: false,
      project_value_range: form.projectValue,
      message: form.message,
    });

    generalErrorMessage.value = "success";
  } catch (error: any) {
    if (error.response) {
      const status = error.response.status;
      const serverMessage = error.response.data?.message || "";
      const validationErrors = error.response.data?.errors || {};

      if (status === 422 && validationErrors) {
        Object.entries(validationErrors).forEach(([key, messages]) => {
          errors.value[key] = Array.isArray(messages) ? messages[0] : messages;
        });
      } else if (status === 400) {
        generalErrorMessage.value = "Invalid input. Please check your data.";
      } else if (status === 401) {
        generalErrorMessage.value = "Authentication required. Please login again.";
      } else if (status === 403) {
        generalErrorMessage.value = "You do not have permission to perform this action.";
      } else if (status === 409) {
        generalErrorMessage.value = "This value already exists. Please choose a different one.";
      } else {
        generalErrorMessage.value = serverMessage || "An unexpected error occurred.";
      }
    } else if (error.request) {
      error.request.message;
      generalErrorMessage.value = "An unknown error has occurred.";
    } else {
      generalErrorMessage.value = "Unexpected error occurred.";
    }
  }
};
</script>