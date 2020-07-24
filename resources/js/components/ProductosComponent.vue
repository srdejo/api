<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <b-col lg="6" class="my-1">
          <b-form-group
            label="Filter"
            label-cols-sm="3"
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
        <b-col lg="6" class="my-1">
          <b-button v-b-modal.modal-1>Launch demo modal</b-button>
        </b-col>
        <div class>
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
            <template v-slot:cell(imagen)="data">
              <img :src="data.value" width="20" height="20" />
            </template>
          </b-table>
        </div>
        <div>
          Mostrar
          <select v-model.number="perPage" class="numero_registros">
            <option value="10">10</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
          de {{rows}} registros
        </div>
        <div>
          <b-pagination
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            aria-controls="productos"
            size="sm"
            align="right"
          ></b-pagination>
        </div>
      </div>
    </div>
    <b-modal id="modal-1" title="BootstrapVue">
      <p class="my-4">Hello from modal!</p>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      filter: "",
      sortBy: "nombre",
      sortDesc: false,
      busy: false,
      fields: [
        { key: "nombre", sortable: true },
        { key: "imagen", sortable: false },
        { key: "precio", sortable: true },
        { key: "precio_oferta", label: "Oferta", sortable: false },
        { key: "fecha_inicio_oferta", label: "Inicio", sortable: true },
        { key: "fecha_fin_oferta", label: "Fin", sortable: false },
      ],
      perPage: 10,
      currentPage: 1,
      rows: 0,

      config: "",
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
        // Must return an array of items or an empty array if an error occurred
        return items || [];
      });
    },
    onRowSelected(items) {
      this.ClienteSeleccionado = "";
      if (items.length > 0) {
        this.ClienteSeleccionado = items[0].id;
      }
    } /*,
    editarCliente() {
      window.location.href = "clientes/" + this.ClienteSeleccionado + "/edit";
    },
    verCliente() {
      window.location.href = "clientes/" + this.ClienteSeleccionado + "";
    },
    eliminarCliente() {
      const config = {
        headers: {
          "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
        }
      };
      axios
        .delete("/clientes/" + this.ClienteSeleccionado, config)
        .then(function(response) {
          if (response.data.error) {
            response.data.error.forEach(function(descripcion) {
              toastr.error(descripcion);
            });
          } else {
            toastr.success(response.data.success);
          }
        })
        .catch(function(response) {
          toastr.error(response.error);
        })
        .finally(
          () => (
            (this.ClienteSeleccionado = ""),
            this.filtrar(),
            (this.modal_eliminar_cliente = false)
          )
        );
    }*/,
  },
};
</script>
