<script>
export default {
  name: "ProjectHistoryTab",

  props: {
    project: {
      type: Object,
      default: () => {
      }
    },
  },
  
  data() {
    return {
      columnContentTypes: [
        'text',
        'text',
        'text',
        'text',
      ],

      headings: [
        'Action',
        'Role',
        'Name',
        'Date',
      ],
    }
  },

  computed: {
    rows() {
      return (this.project?.history || []).map(entry => {
        return [
          entry.action,
          entry.role.charAt(0).toUpperCase() + entry.role.slice(1),
          entry.changed_by_name,
          this.formatDate(entry.created_at)
        ];
      });
    }
  },

  methods: {

    formatDate(date) {
      const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      };

      const formattedDate = new Date(date).toLocaleString('en-GB', options);
      return formattedDate.replace(',', ' /');
    }
  },
}
</script>

<template>
  <Card padding="null">
    <DataTable
        :columnContentTypes="columnContentTypes"
        :headings="headings"
        :rows="rows"
        :pagination="{
          hasNext: true,
          onNext: () => {},
        }"
    />
  </Card>
</template>

<style scoped>

</style>