<script>
export default {
    props: {
        categories: Array
    },

    data() {
        return {
            searchQuery: '', //almacena el texto de búsqueda
            filteredCategories: this.categories, //inicialmente todas las categorías visibles
            selectedCategoryId: null,
            selectedCategory: null,
        };
    },

    methods: {
        selectCategory(category){
            //Establecer la categoría seleccionada
            this.selectedCategoryId = category.id;
            this.selectedCategory = category;
            this.searchQuery = category.name;
            this.filteredCategories = [];
            this.$emit('category-selected', category);
            
        },
        
        selectSubcategory(subcategory){
            this.$emit('subcategory-selected', subcategory);
            this.selectedCategory = null;
            this.selectedCategoryId = null;
        },

        //Filtrar categorías según la búsqueda
        filterCategories() {
            if (this.searchQuery === '') {
                this.filteredCategories = this.categories;
            } else {
                this.filteredCategories = this.categories.filter(category =>
                    category.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        category.subcategories.some(subcategory =>
                        subcategory.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                    )
                );
            }
        },

    } //cierre methods


};
</script>

<template>
<div class="search-container">
    <!-- Barra de búsqueda -->
    <input
      type="text"
      class="form-control search-input"
      v-model="searchQuery"
      placeholder="Busca una categoría o subcategoría..."
      @input="filterCategories"
    />

    <!-- Lista de resultados -->
    <ul v-if="filteredCategories.length && searchQuery" class="results-dropdown">
      <li
        v-for="category in filteredCategories"
        :key="category.id"
        @click="selectCategory(category)"
        class="result-item"
      >
        {{ category.name }}
      </li>
    </ul>

    <!-- Subcategorías si se ha seleccionado una categoría -->
    <div v-if="selectedCategory" class="subcategory-container">
      <h6>Subcategorías de {{ selectedCategory.name }}</h6>
      <ul class="list-unstyled">
        <li
          v-for="subcategory in selectedCategory.subcategories"
          :key="subcategory.id"
          @click="selectSubcategory(subcategory)"
          class="subcategory-item"
        >
          {{ subcategory.name }}
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>
.search-container {
  max-width: 600px;
  margin: 0 auto;
  position: relative;
}

.search-input {
  width: 100%;
  padding: 12px 16px;
  border-radius: 25px;
  border: 1px solid #ccc;
}

.results-dropdown {
  position: absolute;
  /*top: 105%;*/
  bottom: 105%; /*mostrar resultados arriba de la barra de búsqueda */
  width: 100%;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  z-index: 1000;
  max-height: 250px;
  overflow-y: auto;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  /*margin-top: 4px;*/
  margin-bottom: 4px;
  padding: 0;
}

.result-item {
  padding: 10px 16px;
  cursor: pointer;
  list-style: none;
}

.result-item:hover {
  background-color: #f1f1f1;
}

/*Subcategorías */
.subcategory-container {
  position: absolute; /*para que aparezca sobre los resultados*/
  padding: 10px; 
  bottom: 105%; /*mostrar subcategorías arriba */
  left: 0;
  width: 100%;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  z-index: 1000; /* Asegura que las subcategorías estén por encima */
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.subcategory-item {
  padding: 6px 12px;
  cursor: pointer;
  color: #007bff;
}

.subcategory-item:hover {
  text-decoration: underline;
}

</style>