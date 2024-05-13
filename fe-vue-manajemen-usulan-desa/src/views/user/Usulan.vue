<script>
// import { useRoute } from "vue-router";
import axios from "axios";
import BASE_URL from '@/api/config-api';
// import AuthorsTable from "@/views/components/AuthorsTable.vue";

export default {
  components: {
  },
  data() {
    return {
      products: [],
      overlay: false,
    };
  },
  mounted() {

  },
  methods: {
    formatPrice(price) {
      const numericPrice = parseFloat(price);
      return numericPrice.toLocaleString('id-ID');
    },
    async retrieveBuku() {
      try {
        this.overlay = true;
        const response = await axios.get(`${BASE_URL}/buku/get`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem('access_token')
          }
        });
        this.products = response.data;

        if (response.data.length > 0) {
          this.fotoUrl = response.data[0].foto;
        }
      } catch (error) {
        console.error(error);
      } finally {
        this.overlay = false
      }
    },
  },
};
</script>

<template>
  <div class="py-4 container-fluid">
    <div class="row mt-3">
      <v-overlay :model-value="overlay" class="d-flex align-items-center justify-content-center">
        <v-progress-circular color="primary" size="96" indeterminate></v-progress-circular>
      </v-overlay>
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="card px-4">
              <div class="row mt-2 mb-2" style="border-bottom: 1px solid black;">
                <div class="d-flex align-items-center mt-2">
                  <div class="avatar avatar-sm position-relative me-2">
                    <img :src="require('@/assets/img/team-1.jpg')" alt="profile_image"
                      class="shadow-sm w-100 border-radius-lg" />
                  </div>
                  <div class="mt-2">
                    <a class="text-black">Xavier Herlambang</a>
                    <a class="ms-3 text-black" style="font-size: 12px;">99 hours ago</a>
                  </div>
                </div>
                <h4>Judul</h4>
                <div class="row mt-2">
                  <P>
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reiciendis rem dolorem necessitatibus
                    omnis quasi fugit dolore aspernatur, rerum voluptates voluptatem deleniti consequatur soluta
                    veritatis excepturi quia in temporibus odio eum. Labore aspernatur, id asperiores veritatis a
                    adipisci voluptate voluptas, quibusdam aperiam, tempora incidunt dolore! Voluptate sequi iste totam
                    modi animi.
                  </P>
                </div>
                <div class="row mb-2">
                  <v-chip style="width: 70px; cursor: pointer;">
                    <v-icon icon="mdi-arrow-up-bold-outline" start></v-icon>
                    12
                  </v-chip>
                  <v-chip style="width: auto; margin-left: 5px; cursor: pointer;">
                    <v-icon icon="mdi-arrow-down-bold-outline" start></v-icon>
                    12
                  </v-chip>
                  <v-chip style="width: auto; margin-left: 5px; cursor: pointer;">
                    <v-icon icon="mdi-comment-multiple-outline" start></v-icon>
                    123
                  </v-chip>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>
<style>
.user-select-none {
  user-select: none;
}

a {
  text-decoration: none;
  color: unset;
}


.product-single-card {
  /* padding: 10px; */
  border-radius: 5px;
  box-shadow: 1px 1px 15px #cccccc40;
  transition: 0.5s ease-in;
  background-color: white;
}

.product-single-card:hover {
  -webkit-box-shadow: 1px 1px 28.5px -7px #d6d6d6;
  -moz-box-shadow: 1px 1px 28.5px -7px #d6d6d6;
  box-shadow: 1px 1px 28.5px -7px #d6d6d6;
}

.product-single-card .product-info {
  padding: 15px 0 0 0;
}

.product-single-card .product-top-area {
  position: relative;
  display: flex;
  align-items: center;
  overflow: hidden;
  border-radius: 5px;
}

.product-single-card .product-top-area .product-discount {
  position: absolute;
  top: 10px;
  left: 10px;
  background: white;
  border-radius: 3px;
  padding: 5px 10px;
  box-shadow: 1px 1px 28.5px -7px #dddddd;
  user-select: none;
  z-index: 999;
}

.product-single-card .product-top-area .product-img {
  aspect-ratio: 3/4;
  overflow: hidden;
  object-fit: fill;
}

.product-single-card .product-top-area .product-img .first-view {
  transition: 0.5s ease-in;
}

.product-single-card .product-top-area .product-img .hover-view {
  opacity: 0;
  transition: 0.5s ease-in;
}

.product-single-card .product-top-area .product-img img {
  /* width: 250px;  */
  /* height: 150px;  */
  /* object-fit: cover; */
  /* Add a 5px solid white border */
  box-sizing: border-box;
}

.product-single-card .product-top-area .sideicons {
  position: absolute;
  right: 15px;
  display: grid;
  gap: 10px;
}

.product-single-card .product-top-area .sideicons .sideicons-btn {
  background-color: #fff;
  color: #000;
  border-radius: 50%;
  border: none;
  width: 35px;
  height: 35px;
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  visibility: hidden;
  transform: translateX(60px);
  transition: 0.3s ease-in;
  -webkit-box-shadow: 1px 1px 28.5px -7px #dddddd;
  -moz-box-shadow: 1px 1px 28.5px -7px #dddddd;
  box-shadow: 1px 1px 28.5px -7px #dddddd;
}

.product-single-card .product-top-area .sideicons .sideicons-btn:hover {
  color: #fff;
  background-color: #000;
}

.product-single-card .product-top-area .sideicons .sideicons-btn:nth-child(1) {
  transition-delay: 100ms;
}

.product-single-card .product-top-area .sideicons .sideicons-btn:nth-child(2) {
  transition-delay: 200ms;
}

.product-single-card .product-top-area .sideicons .sideicons-btn:nth-child(3) {
  transition-delay: 300ms;
}

.product-single-card .product-top-area .sideicons .sideicons-btn:nth-child(4) {
  transition-delay: 400ms;
}

.product-single-card .product-top-area:hover .sideicons .sideicons-btn {
  opacity: 100%;
  visibility: visible;
  transform: translateX(0);
}

.product-single-card .product-info .product-category {
  font-weight: 600;
  opacity: 60%;
}

.product-single-card .product-info .product-title {
  font-size: 18px;
  font-weight: 600;
}

.product-single-card .product-info .old-price,
.product-single-card .product-info .new-price {
  padding-right: 15px;
  font-size: 18px;
  font-weight: 600;
  letter-spacing: 1px;
}

.product-single-card .product-info .old-price {
  text-decoration: line-through;
  opacity: 70%;
}



.search-container {
  width: 400px;
  display: block;
  margin: 0 auto;
  padding-right: 30px;
}
</style>