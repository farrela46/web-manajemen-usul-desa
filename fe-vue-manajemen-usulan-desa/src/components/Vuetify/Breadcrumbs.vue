<template>
        <v-breadcrumbs class="bg-white rounded shadow" :items="items">
            <template v-slot:divider>
                <v-icon icon="mdi-chevron-right"></v-icon>
            </template>
        </v-breadcrumbs>
</template>

<script>
export default {
    data: () => ({
        items: [
            {
                title: 'Dashboard',
                disabled: false,
                href: 'breadcrumbs_dashboard',
            },
            
            {
                title: 'Link 1',
                disabled: false,
                href: 'breadcrumbs_link_1',
            },
            {
                title: 'Link 2',
                disabled: true,
                href: 'breadcrumbs_link_2',
            },
        ],
    }),
}
</script>