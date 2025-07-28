<script>
import ClientLayout from "@/layout/ClientLayout.vue";
import MobileCard from "@/components/MobileCard.vue";
import PlusCircleIcon from "@/components/icons/PlusCircleIcon.vue";
import StarFullIcon from "@/components/icons/StarFullIcon.vue";
import SearchIcon from "@/components/icons/SearchIcon.vue";

export default {
  name: "ProjectIdeasA",

  components: {MobileCard, ClientLayout},

  data() {
    return {
      PlusCircleIcon,
      StarFullIcon,
      SearchIcon,

      search: '',

      isMobile: screen.width <= 760,
      activeTab: 0,
      tabs: [
        {
          id: 'all',
          content: 'All'
        },
        {
          id: 'development',
          content: 'Development'
        },
        {
          id: 'design',
          content: 'Design'
        },
      ]
    }
  },

  methods: {
    changeTab(tab) {
      this.activeTab = tab;
    },
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
              Browse project ideas designed by our expert network to improve the look, <br/>
              functionally and conversion rate of your store.
            </Text>
          </BlockStack>

          <div style="margin: -15px 20px -20px">
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="104" viewBox="0 0 200 104" fill="none">
              <g filter="url(#filter0_diiii_2314_11712)">
                <rect y="-8" width="56" height="56" rx="6" fill="white"/>
              </g>
              <g filter="url(#filter1_diiii_2314_11712)">
                <rect y="64" width="56" height="56" rx="6" fill="white"/>
              </g>
              <g filter="url(#filter2_diiii_2314_11712)">
                <rect x="72" y="-48" width="56" height="56" rx="6" fill="white"/>
              </g>
              <rect x="72" y="24" width="56" height="56" rx="6" fill="#46FAAA"/>
              <path d="M91.5992 52C91.5992 47.3608 95.36 43.6 99.9992 43.6C104.638 43.6 108.399 47.3608 108.399 52C108.399 52.6627 108.936 53.2 109.599 53.2C110.262 53.2 110.799 52.6627 110.799 52C110.799 46.0353 105.964 41.2 99.9992 41.2C94.0345 41.2 89.1992 46.0353 89.1992 52C89.1992 57.9647 94.0345 62.8 99.9992 62.8C100.662 62.8 101.199 62.2627 101.199 61.6C101.199 60.9373 100.662 60.4 99.9992 60.4C95.36 60.4 91.5992 56.6392 91.5992 52Z" fill="#262A46"/>
              <path d="M102.459 51.6885C102.551 52.3448 103.158 52.8021 103.814 52.7098C104.471 52.6176 104.928 52.0108 104.836 51.3545C104.712 50.4779 104.349 49.6524 103.786 48.9694C103.223 48.2863 102.482 47.7723 101.645 47.4841C100.808 47.1959 99.9076 47.1447 99.0434 47.3363C98.1791 47.5279 97.3847 47.9548 96.7479 48.5697C96.1112 49.1846 95.6568 49.9637 95.4352 50.8207C95.2135 51.6777 95.2332 52.5794 95.492 53.4259C95.7508 54.2725 96.2387 55.031 96.9017 55.6175C97.5647 56.2041 98.377 56.5959 99.2488 56.7496C99.9015 56.8647 100.524 56.4289 100.639 55.7762C100.754 55.1236 100.318 54.5012 99.6655 54.3861C99.2296 54.3092 98.8235 54.1133 98.492 53.82C98.1605 53.5268 97.9166 53.1475 97.7872 52.7242C97.6578 52.301 97.6479 51.8501 97.7587 51.4216C97.8696 50.9931 98.0967 50.6036 98.4151 50.2961C98.7335 49.9887 99.1307 49.7752 99.5628 49.6794C99.995 49.5836 100.445 49.6092 100.864 49.7533C101.282 49.8974 101.653 50.1544 101.934 50.496C102.216 50.8375 102.397 51.2502 102.459 51.6885Z" fill="#262A46"/>
              <path d="M102.577 53.557C102.286 53.4545 101.963 53.5279 101.745 53.7457C101.527 53.9635 101.454 54.2871 101.556 54.5776L104.95 64.1943C105.048 64.4723 105.291 64.6738 105.583 64.7187C105.874 64.7636 106.167 64.6446 106.344 64.4089L108.219 61.9166L109.879 63.5766C110.191 63.889 110.698 63.889 111.01 63.5766L111.576 63.0109C111.888 62.6985 111.888 62.1919 111.576 61.8795L109.916 60.2195L112.408 58.3448C112.644 58.1676 112.763 57.8751 112.718 57.5837C112.673 57.2923 112.471 57.0492 112.193 56.9511L102.577 53.557Z" fill="#262A46"/>
              <g filter="url(#filter3_diiii_2314_11712)">
                <rect x="72" y="96" width="56" height="56" rx="6" fill="white"/>
              </g>
              <g filter="url(#filter4_diiii_2314_11712)">
                <rect x="144" y="-8" width="56" height="56" rx="6" fill="white"/>
              </g>
              <g filter="url(#filter5_diiii_2314_11712)">
                <rect x="144" y="64" width="56" height="56" rx="6" fill="white"/>
              </g>
              <defs>
                <filter id="filter0_diiii_2314_11712" x="0" y="-8" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                </filter>
                <filter id="filter1_diiii_2314_11712" x="0" y="64" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                </filter>
                <filter id="filter2_diiii_2314_11712" x="72" y="-48" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                </filter>
                <filter id="filter3_diiii_2314_11712" x="72" y="96" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                </filter>
                <filter id="filter4_diiii_2314_11712" x="144" y="-8" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                </filter>
                <filter id="filter5_diiii_2314_11712" x="144" y="64" width="56" height="57" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                  <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="out"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0 0.101961 0 0 0 0.07 0"/>
                  <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2314_11712"/>
                  <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2314_11712" result="shape"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0 0.8 0 0 0 0.5 0"/>
                  <feBlend mode="multiply" in2="shape" result="effect2_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dy="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.17 0"/>
                  <feBlend mode="multiply" in2="effect2_innerShadow_2314_11712" result="effect3_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="-1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect3_innerShadow_2314_11712" result="effect4_innerShadow_2314_11712"/>
                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                  <feOffset dx="1"/>
                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.13 0"/>
                  <feBlend mode="multiply" in2="effect4_innerShadow_2314_11712" result="effect5_innerShadow_2314_11712"/>
                </filter>
              </defs>
            </svg>
          </div>
        </InlineStack>
      </MobileCard>
    </Page>

    <template v-if="isMobile">
      <BlockStack gap="600" style="padding: 32px 16px; filter: blur(4px);">
        <InlineStack align="space-between" blockAlign="center">
          <Text variant="headingLg" as="p">Project Ideas</Text>
        </InlineStack>

        <TextField
            style="min-width: 220px"
            :label="null"
            type="text"
            v-model="search"
            autoComplete="off"
            placeholder="Search projects ..."
        >
          <template #prefix>
            <Icon disabled :source="SearchIcon" />
          </template>
        </TextField>

        <BlockStack gap="300">
          <Tabs style="padding: 0"
                disabled
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />

          <MobileCard padding="800">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                <Text as="p" variant="headingMd">$300.00</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                <Text as="p" variant="bodyMd">
                  Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>

                  <InlineStack gap="050">
                    <Icon :source="StarFullIcon"></Icon>
                    <Text as="p" variant="bodyMd">
                      4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                    </Text>
                  </InlineStack>
                </BlockStack>
              </InlineStack>

              <Button variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
            </BlockStack>
          </MobileCard>
          <MobileCard padding="800">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                <Text as="p" variant="headingMd">$400.00</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                <Text as="p" variant="bodyMd">
                  Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>

                  <InlineStack gap="050">
                    <Icon :source="StarFullIcon"></Icon>
                    <Text as="p" variant="bodyMd">
                      4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                    </Text>
                  </InlineStack>
                </BlockStack>
              </InlineStack>

              <Button disabled variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
            </BlockStack>
          </MobileCard>
          <MobileCard padding="800">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                <Text as="p" variant="headingMd">$300.00</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                <Text as="p" variant="bodyMd">
                  Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>

                  <InlineStack gap="050">
                    <Icon :source="StarFullIcon"></Icon>
                    <Text as="p" variant="bodyMd">
                      4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                    </Text>
                  </InlineStack>
                </BlockStack>
              </InlineStack>

              <Button disabled variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
            </BlockStack>
          </MobileCard>
          <MobileCard padding="800">
            <BlockStack gap="400">
              <BlockStack gap="100">
                <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                <Text as="p" variant="headingMd">$400.00</Text>
              </BlockStack>

              <BlockStack gap="100">
                <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                <Text as="p" variant="bodyMd">
                  Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                </Text>
              </BlockStack>

              <Divider />

              <InlineStack gap="200">

                <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                <BlockStack gap="050">
                  <Text as="p" variant="headingSm">
                    *Expert Full Name*
                  </Text>

                  <InlineStack gap="050">
                    <Icon :source="StarFullIcon"></Icon>
                    <Text as="p" variant="bodyMd">
                      4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                    </Text>
                  </InlineStack>
                </BlockStack>
              </InlineStack>

              <Button disabled variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
            </BlockStack>
          </MobileCard>
        </BlockStack>
      </BlockStack>
    </template>
    <template v-else>
      <Page style="padding-top: 56px; padding-bottom: 56px; filter: blur(4px);"
            title="Project Ideas"
      >
        <BlockStack gap="600">
          <Tabs style="padding: 0" disabled
                :tabs="tabs"
                :selected="activeTab"
                @select="changeTab"
          />

          <BlockStack gap="300">
            <Card padding="800">
              <BlockStack gap="400">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                    <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                    <Text as="p" variant="headingMd">$300.00</Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                  <Text as="p" variant="bodyMd">
                    Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="headingSm">
                        *Expert Full Name*
                      </Text>

                      <InlineStack gap="050">
                        <Icon :source="StarFullIcon"></Icon>
                        <Text as="p" variant="bodyMd">
                          4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>

                  <Button disabled variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
                </InlineStack>
              </BlockStack>
            </Card>
            <Card padding="800">
              <BlockStack gap="400">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                    <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                    <Text as="p" variant="headingMd">$400.00</Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                  <Text as="p" variant="bodyMd">
                    Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="headingSm">
                        *Expert Full Name*
                      </Text>

                      <InlineStack gap="050">
                        <Icon :source="StarFullIcon"></Icon>
                        <Text as="p" variant="bodyMd">
                          4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>

                  <Button disabled variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
                </InlineStack>
              </BlockStack>
            </Card>
            <Card padding="800">
              <BlockStack gap="400">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                    <Text as="p" variant="bodyMd" style="color: #005BD3">Development</Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                    <Text as="p" variant="headingMd">$300.00</Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                  <Text as="p" variant="bodyMd">
                    Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="headingSm">
                        *Expert Full Name*
                      </Text>

                      <InlineStack gap="050">
                        <Icon :source="StarFullIcon"></Icon>
                        <Text as="p" variant="bodyMd">
                          4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>

                  <Button disabled variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
                </InlineStack>
              </BlockStack>
            </Card>
            <Card padding="800">
              <BlockStack gap="400">
                <InlineStack align="space-between">
                  <BlockStack gap="100">
                    <Text as="p" variant="headingMd">*Project Idea Title*</Text>

                    <Text as="p" variant="bodyMd" style="color: #005BD3">Design</Text>
                  </BlockStack>

                  <BlockStack gap="100">
                    <Text as="p" variant="bodySm" tone="subdued">Estimated Cost</Text>

                    <Text as="p" variant="headingMd">$400.00</Text>
                  </BlockStack>
                </InlineStack>

                <BlockStack gap="100">
                  <Text as="p" variant="bodySm" tone="subdued">Project Description</Text>
                  <Text as="p" variant="bodyMd">
                    Our goal is to create a seamless fusion of aesthetics and functionality, ensuring that every visit to our clients' online stores is an unforgettable experience. From sleek design elements to intuitive navigation features, we're poised to revolutionize the way customers interact with products online.
                  </Text>
                </BlockStack>

                <Divider />

                <InlineStack align="space-between" blockAlign="center">
                  <InlineStack gap="200">

                    <Avatar style="border-radius: 100%" customer name="Test" size="xl" />

                    <BlockStack gap="050">
                      <Text as="p" variant="headingSm">
                        *Expert Full Name*
                      </Text>

                      <InlineStack gap="050">
                        <Icon :source="StarFullIcon"></Icon>
                        <Text as="p" variant="bodyMd">
                          4.9 <Text as="span" tone="subdued">(28 Completed Projects)</Text>
                        </Text>
                      </InlineStack>
                    </BlockStack>
                  </InlineStack>

                  <Button disabled variant="primary" :icon="PlusCircleIcon">Hire Me</Button>
                </InlineStack>
              </BlockStack>
            </Card>
          </BlockStack>
        </BlockStack>
      </Page>
    </template>
  </ClientLayout>
</template>

<style scoped>

</style>