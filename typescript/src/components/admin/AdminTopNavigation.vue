<template>
    <header class="flex items-center justify-between p-4 border-b bg-white">
        <div class="text-xl font-bold flex items-center">
            <Logo />
        </div>

        <div class="flex items-center gap-4">
            <NotificationsDropdown :notificationCount="notificationCount" />

            <MessageDropDown :messageCount="messageCount" />
            <AdminProfileDropdown :profileImage="profileImage ?? ''" />
        </div>
    </header>
</template>

<script setup lang="ts">
    import Logo from '../../assets/icons/logo.svg';
    import NotificationsDropdown from "@/components/client/NotificationsDropdown.vue";
    import MessageDropDown from "@/components/client/MessageDropDown.vue";
    import AdminProfileDropdown from "@/components/admin/AdminProfileDropdown.vue";

    defineProps<{
        messageCount: number
        notificationCount: number
        profileImage: string | null
    }>()
</script>
