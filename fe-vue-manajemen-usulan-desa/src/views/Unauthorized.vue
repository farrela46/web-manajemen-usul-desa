<template>
    <div class="unauthorized-page">
        <h1>401</h1>
        <h2>You are not authorized to access this page</h2>
        <h6 @click="back" style="cursor: pointer; text-decoration: underline;">Go Back</h6>
    </div>
</template>

<script>
export default {
    name: 'Unauthorized',
    created() {
        this.store = this.$store;
        this.body = document.getElementsByTagName("body")[0];
        this.setupPage();
    },
    beforeUnmount() {
        this.restorePage();
    },
    methods: {
        back() {
            window.history.back();
        },
        setupPage() {
            this.store.state.hideConfigButton = true;
            this.store.state.showNavbar = false;
            this.store.state.showSidenav = false;
            this.store.state.showFooter = false;
            this.body.classList.remove("bg-gray-100");
        },
        restorePage() {
            this.store.state.hideConfigButton = false;
            this.store.state.showNavbar = true;
            this.store.state.showSidenav = false;
            this.store.state.showFooter = true;
            this.body.classList.add("bg-gray-100");
        },
    }
};
</script>

<style>
.unauthorized-page {
    text-align: center;
    padding: 50px;
}

.unauthorized-page h1 {
    font-size: 100px;
    margin-bottom: 20px;
}

.unauthorized-page h2 {
    font-size: 24px;
    margin-bottom: 20px;
}
</style>