<script>


import MiniStatisticsCard from "@/examples/Cards/MiniStatisticsCard.vue";
import GradientLineChart from "@/examples/Charts/GradientLineChart.vue";
import Carousel from "../../views/components/Carousel.vue";




export default {
  components: {
    MiniStatisticsCard,
    GradientLineChart,
    Carousel,
  },
  data() {
    return {

    };
  },
  created() {
    this.store = this.$store;
    this.body = document.getElementsByTagName("body")[0];
    this.setupPage();
  },
  beforeUnmount() {
    this.restorePage();
  },
  methods: {
    setupPage() {
      this.store.state.hideConfigButton = true;
      this.store.state.showNavbar = true;
      this.store.state.showSidenav = true;
      this.store.state.showFooter = false;
      this.body.classList.remove("bg-gray-100");
    },
    restorePage() {
      this.store.state.hideConfigButton = false;
      this.store.state.showNavbar = true;
      this.store.state.showSidenav = true;
      this.store.state.showFooter = true;
      this.body.classList.add("bg-gray-100");
    }
  }
};

</script>
<template>
  <div class="py-4 container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-3 col-md-6 col-12">
            <mini-statistics-card title="Today's Money" value="$53,000" description="<span
                class='text-sm font-weight-bolder text-success'
                >+55%</span> since yesterday" :icon="{
                  component: 'ni ni-money-coins',
                  background: 'bg-gradient-primary',
                  shape: 'rounded-circle',
                }" />
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <mini-statistics-card title="Today's Users" value="2,300" description="<span
                class='text-sm font-weight-bolder text-success'
                >+3%</span> since last week" :icon="{
                  component: 'ni ni-world',
                  background: 'bg-gradient-danger',
                  shape: 'rounded-circle',
                }" />
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <mini-statistics-card title="New Clients" value="+3,462" description="<span
                class='text-sm font-weight-bolder text-danger'
                >-2%</span> since last quarter" :icon="{
                  component: 'ni ni-paper-diploma',
                  background: 'bg-gradient-success',
                  shape: 'rounded-circle',
                }" />
          </div>
          <div class="col-lg-3 col-md-6 col-12">
            <mini-statistics-card title="Sales" value="$103,430" description="<span
                class='text-sm font-weight-bolder text-success'
                >+5%</span> than last month" :icon="{
                  component: 'ni ni-cart',
                  background: 'bg-gradient-warning',
                  shape: 'rounded-circle',
                }" />
          </div>
        </div>
        <div class="row">
          <div class="col-lg-7 mb-lg">
            <!-- line chart -->
            <div class="card z-index-2">
              <gradient-line-chart id="chart-line" title="Sales Overview" description="<i class='fa fa-arrow-up text-success'></i>
      <span class='font-weight-bold'>4% more</span> in 2021" :chart="{
                  labels: [
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec',
                  ],
                  datasets: [
                    {
                      label: 'Mobile Apps',
                      data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    },
                  ],
                }" />
            </div>
          </div>
          <div class="col-lg-5">
            <carousel />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
