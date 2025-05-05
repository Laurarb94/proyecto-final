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
        };
    },

    methods: {
        selectCategory(category){
            //Establecer la categoría seleccionada
            this.selectedCategoryId = category.id;
            this.$emit('category-selected', category);
        },
        
        selectSubcategory(subcategory){
            this.$emit('subcategory-selected', subcategory);
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

        /*Icono según nombre de la categoría
        getCategoryIconKey(categoryName){
            const iconMap = {
                'Informática': 'laptop',
                'Salud': 'staff-snake',
                'Marketing': 'lightbulb',
                'Ciencias Sociales': 'graduation-cap',
                'Humanidades': 'book',
                'Hosteleria y Turismo': 'utensils',
                'Matemáticas y Estadística':'diagram-project',
                'Ingenierias': 'tools',
            }
            // Devuelve el icono correspondiente si existe, o un valor por defecto (por ejemplo, 'question-circle')
             return iconMap[categoryName] || 'question-circle'; 
        }*/



    } //cierre methods


};
</script>

<template>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Explora las categorías y encuentra tu trabajo ideal</h5>
    </div>

    <!--Barra de búsqueda-->
    <div class="mb-3">
        <input
           type="text"
           class="form-control"
           v-model="searchQuery"
           placeholder="Busca una categoría..."
           @input="filterCategories"
        />
    </div>

    <!--Resultados filtrados de categoría-->
    <div class="row">
        <div class="col-6 col-md-3" v-for="category in filteredCategories" :key="category.id">
            <div class="category-item">
                <div class="category-link" @click="selectCategory(category)">
                    <div class="category-icon mx-auto">
                        <!---<font-awesome-icon :icon="['fas', getCategoryIcon(category.name)]" />-->
                    </div>
                    <p class="small mt-2 text-center"> {{ category.name }} </p>
                </div>

                <div v-if="selectedCategoryId === category.id" class="subcategory-list mt-2">
                    <div class="collapse show">
                        <ul class="list-unstyled">
                            <li v-for="subcategory in category.subcategories" :key="subcategory.id" @click="selectSubcategory(subcategory)">
                                <a href="#" class="subcategory-link">{{ subcategory.name }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
/* Estilos para la barra de búsqueda */
input.form-control {
  width: 100%;
  border-radius: 5px;
  padding: 10px;
  margin-bottom: 20px;
}

/* Agregar efectos a las categorías y subcategorías */
.category-item:hover {
  background-color: #f8f9fa;
  cursor: pointer;
}

.subcategory-link {
  color: #007bff;
  text-decoration: none;
}

.subcategory-link:hover {
  text-decoration: underline;
}
</style>