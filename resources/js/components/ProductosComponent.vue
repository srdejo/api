<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <b-row class="justify-content-center">
          <b-form @submit="onSubmit" @reset="resetModal" v-if="show">
            <b-form-group id="input-group-2" label="Nombre:" label-for="input-2">
              <b-form-input
                id="input-2"
                v-model="form.nombre"
                required
                placeholder="Ej: Domi Burguer"
                invalid-feedback="Nombre es obligatorio"
                type="text"
              ></b-form-input>
            </b-form-group>

            <b-form-group id="input-group-precio" label="Precio:" label-for="input-precio">
              <b-form-input
                id="input-precio"
                v-model="form.precio"
                required
                placeholder="Ej: $3.000"
                type="number"
              ></b-form-input>
            </b-form-group>

            <b-form-group
              id="input-group-precio-oferta"
              label="Precio Oferta:"
              label-for="input-precio-oferta"
            >
              <b-form-input
                id="input-precio-oferta"
                v-model="form.precio_oferta"
                required
                placeholder="Ej: $2.000"
                type="number"
              ></b-form-input>
            </b-form-group>

            <label>Inicio Oferta:</label>
            <b-input-group class="mb-3" label-for="input-fecha-inicio-oferta">
              <b-form-input
                id="input-fecha-inicio-oferta"
                v-model="form.fecha_inicio_oferta"
                type="text"
                placeholder="YYYY-MM-DD"
                autocomplete="off"
                readonly
              ></b-form-input>
              <b-input-group-append>
                <b-form-datepicker
                  v-model="form.fecha_inicio_oferta"
                  button-only
                  right
                  locale="en-US"
                  aria-controls="input-fecha-inicio-oferta"
                ></b-form-datepicker>
              </b-input-group-append>
            </b-input-group>

            <label>Fin Oferta:</label>
            <b-input-group class="mb-3" label-for="input-fecha-fin-oferta">
              <b-form-input
                id="input-fecha-fin-oferta"
                v-model="form.fecha_fin_oferta"
                type="text"
                placeholder="YYYY-MM-DD"
                autocomplete="off"
                readonly
              ></b-form-input>
              <b-input-group-append>
                <b-form-datepicker
                  v-model="form.fecha_fin_oferta"
                  button-only
                  right
                  locale="en-US"
                  aria-controls="input-fecha-fin-oferta"
                ></b-form-datepicker>
              </b-input-group-append>
            </b-input-group>

            <b-form-group
              id="input-group-1"
              label="Imagen del producto:"
              label-for="imagen"
              description="Las dimensiones recomendadas son ## x ##."
            >
              <b-form-file
                id="imagen"
                v-model="form.imagen"
                placeholder="Escoja una imagen o arrastrela aquí..."
                drop-placeholder="Suelte su imagen aquí..."
              ></b-form-file>
            </b-form-group>
            <b-button type="reset" variant="outline-secondary">Reset</b-button>
            <b-button type="submit" variant="secondary">Submit</b-button>
          </b-form>
        </b-row>
        <hr />
        <b-row>
          <b-col lg="6" class="my-1">
            <b-form-group
              label="Filter"
              label-cols-sm="2"
              label-align-sm="right"
              label-size="sm"
              label-for="filterInput"
              class="mb-0"
            >
              <b-input-group size="sm">
                <b-form-input
                  v-model="filter"
                  type="search"
                  id="filterInput"
                  placeholder="Type to Search"
                ></b-form-input>
                <b-input-group-append>
                  <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col lg="6">
            <b-button
              v-b-modal.modal-producto
              size="sm"
              variant="outline-secondary"
              class="align-middle float-right"
            >Agregar</b-button>
          </b-col>
        </b-row>
        <b-row>
          <b-table
            id="productos"
            selectable
            select-mode="single"
            :busy="busy"
            :items="myProvider"
            :fields="fields"
            :per-page="perPage"
            :current-page="currentPage"
            :sort-by="sortBy"
            :sort-desc="sortDesc"
            :filter="filter"
            :hover="true"
            @row-selected="onRowSelected"
          >
            <template v-slot:cell(url_imagen)="data">
              <img :src="data.value" width="20" height="20" />
            </template>
          </b-table>
        </b-row>
        <b-row>
          <b-col>
            Mostrar
            <select v-model.number="perPage" class="numero_registros">
              <option value="10">10</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            de {{rows}} registros
          </b-col>
          <b-col>
            <b-pagination
              v-model="currentPage"
              :total-rows="rows"
              :per-page="perPage"
              aria-controls="productos"
              size="sm"
              align="right"
            ></b-pagination>
          </b-col>
        </b-row>
      </div>
    </div>
    <b-modal
      id="modal-producto"
      title="Agregar Producto"
      @show="resetModal"
      @hidden="resetModal"
      @ok="onSubmit"
    ></b-modal>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      form: "",
      filter: "",
      sortBy: "nombre",
      sortDesc: false,
      busy: false,
      fields: [
        { key: "nombre", sortable: true },
        { key: "url_imagen", label: 'Imagen', sortable: false },
        { key: "precio", sortable: true },
        { key: "precio_oferta", label: "Oferta", sortable: false },
        { key: "fecha_inicio_oferta", label: "Inicio", sortable: true },
        { key: "fecha_fin_oferta", label: "Fin", sortable: false },
      ],
      perPage: 10,
      currentPage: 1,
      rows: 0,

      config: "",

      show: true,
      form: {
        nombre: "",
        imagen: null,
        precio: null,
        precio_oferta: "",
        fecha_inicio_oferta: "",
        fecha_fin_oferta: "",
      },
    };
  },
  mounted() {
    this.config = {
      headers: {
        "X-CSRF-TOKEN": document
          .querySelector('meta[name="csrf-token"]')
          .getAttribute("content"),
      },
    };
  },
  methods: {
    myProvider(ctx) {
      this.busy = true;
      const promise = axios.get(
        "/productos",
        {
          params: {
            page: ctx.currentPage,
            filter: this.filter,
            perPage: ctx.perPage,
            sortBy: ctx.sortBy,
            sortDesc: ctx.sortDesc,
          },
        },
        this.config
      );

      // Must return a promise that resolves to an array of items
      return promise.then((res) => {
        // Pluck the array of items off our axios response
        this.rows = res.data.meta.total;
        const items = res.data.data;
        this.busy = false;
        console.log(items)
        // Must return an array of items or an empty array if an error occurred
        return items || [];
      });
    },
    onRowSelected(items) {
      this.ClienteSeleccionado = "";
      if (items.length > 0) {
        this.ClienteSeleccionado = items[0].id;
      }
    },
    checkFormValidity() {
      const valid = this.$refs.form.checkValidity();
      return valid;
    },
    resetModal() {
      this.form = {
        nombre: "",
        imagen: "",
        precio: "",
        precio_oferta: "",
        fecha_inicio_oferta: "",
        fecha_fin_oferta: "",
      };
    },
    onSubmit(evt) {
      evt.preventDefault();
      let formData = new FormData();
      formData.append("nombre", this.form.nombre);
      formData.append("imagen", this.form.imagen);
      formData.append("precio", this.form.precio);
      formData.append("precio_oferta", this.form.precio_oferta);
      formData.append("fecha_inicio_oferta", this.form.fecha_inicio_oferta);
      formData.append("fecha_fin_oferta", this.form.fecha_fin_oferta);

      this.config.headers["content-type"] = "multipart/form-data";
      axios
        .post("/producto", formData, this.config)
        .then(function (response) {
          if (response.data.error) {
              console.log(response);
          } else {
            console.log(response.data.success);
          }
        })
        .catch(function (response) {
          console.log(response);
        })
        .finally(() => {});
    },
  },
};
</script>
