<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import AddTeamModal from "@/components/modals/AddTeamModal.vue";
import RemoveTeamModal from "@/components/modals/RemoveTeamModal.vue";

import AddIcon from "@/components/icons/AddIcon.vue";
import AlertCircleIcon from "@/components/icons/AlertCircleIcon.vue";
import XIcon from "@/components/icons/XIcon.vue";

import emptyState from '@/assets/empty-state.png';
import MobileCard from "@/components/MobileCard.vue";
import InputBtn from "@/components/misc/InputBtn.vue";


export default {
  name: "TeamPage",

  components: {
    InputBtn,
    MobileCard,
    ClientLayout,
    AddTeamModal,
    RemoveTeamModal
  },

  data() {
    return {
      AddIcon,
      AlertCircleIcon,
      XIcon,
      emptyState,
      isMobile: screen.width <= 760,

      selected1: 'admin',
      selected2: 'team',

      addTeamModal: false,
      removeTeamModal: false,

      options: [
        {label: 'Admin', value: 'admin'},
        {label: 'Team Member', value: 'team'}
      ]
    }
  },

  methods: {
    toggleAddTeamModal() {
      //this.addTeamModal = !this.addTeamModal;
    },
    toggleRemoveTeamModal() {
      //this.removeTeamModal = !this.removeTeamModal;
    }
  }
}
</script>

<template>
  <ClientLayout>
    <Page style="padding-top: 56px">
      <MobileCard>
        <InlineStack align="space-between">
          <BlockStack gap="200">
            <Text variant="headingLg">Coming soon!</Text>
            <Text variant="bodyLg">
              Add your colleagues and collaborate together. Save time and boost<br/>
              communication and productivity on your projects.
            </Text>
          </BlockStack>

          <div style="margin: -15px 20px -20px">
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="104" viewBox="0 0 200 104" fill="none">
              <g filter="url(#filter0_diiii_2314_11807)">
                <rect y="-8" width="56" height="56" rx="6" fill="white"/>
              </g>
              <g filter="url(#filter1_diiii_2314_11807)">
                <rect y="64" width="56" height="56" rx="6" fill="white"/>
              </g>
              <g filter="url(#filter2_diiii_2314_11807)">
                <rect x="72" y="-48" width="56" height="56" rx="6" fill="white"/>
              </g>
              <rect x="72" y="24" width="56" height="56" rx="6" fill="#46FAAA"/>
              <path d="M110.002 38.8001C110.002 38.1374 109.464 37.6001 108.802 37.6001C108.139 37.6001 107.602 38.1374 107.602 38.8001V40.4001H106.002C105.339 40.4001 104.802 40.9374 104.802 41.6001C104.802 42.2628 105.339 42.8001 106.002 42.8001H107.602V44.4001C107.602 45.0628 108.139 45.6001 108.802 45.6001C109.464 45.6001 110.002 45.0628 110.002 44.4001V42.8001H111.602C112.264 42.8001 112.802 42.2628 112.802 41.6001C112.802 40.9374 112.264 40.4001 111.602 40.4001H110.002V38.8001Z" fill="#262A46"/>
              <path fill-rule="evenodd" clip-rule="evenodd" d="M98.4017 40.8001C95.3089 40.8001 92.8017 43.3073 92.8017 46.4001C92.8017 49.4929 95.3089 52.0001 98.4017 52.0001C101.494 52.0001 104.002 49.4929 104.002 46.4001C104.002 43.3073 101.494 40.8001 98.4017 40.8001ZM95.2017 46.4001C95.2017 44.6328 96.6343 43.2001 98.4017 43.2001C100.169 43.2001 101.602 44.6328 101.602 46.4001C101.602 48.1674 100.169 49.6001 98.4017 49.6001C96.6343 49.6001 95.2017 48.1674 95.2017 46.4001Z" fill="#262A46"/>
              <path fill-rule="evenodd" clip-rule="evenodd" d="M107.176 58.7629C105.406 55.5764 102.047 53.6001 98.4016 53.6001C94.7564 53.6001 91.3976 55.5764 89.6273 58.7629L88.9287 60.0205C88.1362 61.447 89.1677 63.2001 90.7995 63.2001H106.004C107.636 63.2001 108.667 61.447 107.875 60.0205L107.176 58.7629ZM91.7253 59.9285C93.0723 57.5039 95.628 56.0001 98.4016 56.0001C101.175 56.0001 103.731 57.5039 105.078 59.9285L105.562 60.8001H91.2411L91.7253 59.9285Z" fill="#262A46"/>
              <g filter="url(#filter3_diiii_2314_11807)">
                <rect x="72" y="96" width="56" height="56" rx="6" fill="white"/>
              </g>
              <g filter="url(#filter4_diiii_2314_11807)">
                <rect x="144" y="-8" width="56" height="56" rx="6" fill="white"/>
              </g>
              <g filter="url(#filter5_diiii_2314_11807)">
                <rect x="144" y="64" width="56" height="56" rx="6" fill="white"/>
              </g>
              <defs>
                <filter id="filter0_diiii_2314_11807" x="0" y="-8" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11807"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11807" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11807" result="effect3_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11807" result="effect4_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11807" result="effect5_innerShadow_2314_11807"/>
                </filter>
                <filter id="filter1_diiii_2314_11807" x="0" y="64" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11807"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11807" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11807" result="effect3_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11807" result="effect4_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11807" result="effect5_innerShadow_2314_11807"/>
                </filter>
                <filter id="filter2_diiii_2314_11807" x="72" y="-48" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11807"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11807" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11807" result="effect3_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11807" result="effect4_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11807" result="effect5_innerShadow_2314_11807"/>
                </filter>
                <filter id="filter3_diiii_2314_11807" x="72" y="96" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11807"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11807" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11807" result="effect3_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11807" result="effect4_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11807" result="effect5_innerShadow_2314_11807"/>
                </filter>
                <filter id="filter4_diiii_2314_11807" x="144" y="-8" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11807"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11807" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11807" result="effect3_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11807" result="effect4_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11807" result="effect5_innerShadow_2314_11807"/>
                </filter>
                <filter id="filter5_diiii_2314_11807" x="144" y="64" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11807"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11807" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11807" result="effect3_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11807" result="effect4_innerShadow_2314_11807"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11807" result="effect5_innerShadow_2314_11807"/>
                </filter>
              </defs>
            </svg>
          </div>
        </InlineStack>
      </MobileCard>
    </Page>

    <template v-if="isMobile">
      <BlockStack @click.stop="null" gap="600" style="padding: 32px 16px; filter: blur(4px);">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">My Team</Text>

          <InputBtn :icon="AddIcon" @click="toggleAddTeamModal">Add Member</InputBtn>
        </InlineStack>

        <BlockStack gap="300">
          <MobileCard>
            <BlockStack gap="400">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *User Name*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *User Email*
                </Text>
              </BlockStack>

              <div style="width: 236px">
                <Text variant="bodyMd" as="p" tone="subdued">
                  Account Owner
                </Text>
              </div>
            </BlockStack>
          </MobileCard>


          <MobileCard>
            <BlockStack gap="400">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *Team Name 1*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team Email 1*
                </Text>
              </BlockStack>

              <InlineStack gap="400" blockAlign="center">
                <div style="flex: auto">
                  <Select
                      :options="options"
                      v-model="selected1"
                  />
                </div>

                <div>
                  <Icon :source="XIcon" @click="toggleRemoveTeamModal"></Icon>
                </div>
              </InlineStack>
            </BlockStack>
          </MobileCard>

          <MobileCard>
            <BlockStack gap="400">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *Team Name 2*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team Email 2*
                </Text>
              </BlockStack>

              <InlineStack gap="400" blockAlign="center">
                <div style="flex: auto">
                  <Select
                      :options="options"
                      v-model="selected2"
                  />
                </div>

                <div style="row-span: 1">
                  <Icon :source="XIcon" @click="toggleRemoveTeamModal"></Icon>
                </div>
              </InlineStack>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>
    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px; filter: blur(4px);"
            title="My Team"
      >
        <template #primaryAction>
          <InputBtn :icon="AddIcon" @click="toggleAddTeamModal">Add Member</InputBtn>
        </template>
        <template #secondaryActions>
          <Popover
              :active="false"
              autofocusTarget="first-node"
              disabled
          >
            <template #activator>
              <Button size="large" disabled
                      :icon="AlertCircleIcon"
                      :disclosure="'down'">Permissions</Button>
            </template>
            <ActionList
                actionRole="menuitem"
                :items="statusList"
            ></ActionList>
          </Popover>
        </template>

        <BlockStack gap="300">
          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *User Name*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *User Email*
                </Text>
              </BlockStack>

              <div style="width: 236px">
                <Text variant="bodyMd" as="p" tone="subdued">
                  Account Owner
                </Text>
              </div>
            </InlineStack>
          </Card>

          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *Team Name 1*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team Email 1*
                </Text>
              </BlockStack>

              <InlineStack gap="400">
                <div style="width: 200px">
                  <Select disabled
                      :options="options"
                      v-model="selected1"
                  />
                </div>

                <div>
                  <Icon :source="XIcon" @click="toggleRemoveTeamModal"></Icon>
                </div>
              </InlineStack>
            </InlineStack>
          </Card>

          <Card padding="600">
            <InlineStack align="space-between" blockAlign="center">
              <BlockStack>
                <Text variant="bodyMd" as="p" fontWeight="semibold">
                  *Team Name 2*
                </Text>

                <Text variant="bodyMd" as="p" tone="subdued">
                  *Team Email 2*
                </Text>
              </BlockStack>

              <InlineStack gap="400">
                <div style="width: 200px">
                  <Select disabled
                      :options="options"
                      v-model="selected2"
                  />
                </div>

                <div>
                  <Icon :source="XIcon" @click="toggleRemoveTeamModal"></Icon>
                </div>
              </InlineStack>
            </InlineStack>
          </Card>
        </BlockStack>
      </Page>
    </template>

    <AddTeamModal v-show="addTeamModal" @close="toggleAddTeamModal" />
    <RemoveTeamModal v-show="removeTeamModal" @close="toggleRemoveTeamModal" />
  </ClientLayout>
</template>

<style scoped>

</style>