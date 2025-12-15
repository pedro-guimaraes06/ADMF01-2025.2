<template>
  <v-app>
    <!-- App Bar -->
    <v-app-bar app color="primary" dark elevation="4">
      <v-toolbar-title class="d-flex align-center flex-shrink-0" style="margin-right: 50px; min-width: 150px;">
        <v-icon large class="mr-2">mdi-bug</v-icon>
        <div>
          <div class="text-h6 font-weight-bold">SAD Dengue</div>
        </div>
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <!-- Navigation Tabs -->
      <v-tabs
        background-color="transparent"
        dark
        optional
        class="hidden-sm-and-down"
      >
        <v-tab
          v-for="item in menuItems"
          :key="item.path"
          :to="item.path"
          exact
          class="text-none"
        >
          <v-icon left>{{ item.icon }}</v-icon>
          {{ item.title }}
        </v-tab>
      </v-tabs>

      <!-- Mobile Menu -->
      <v-app-bar-nav-icon
        class="hidden-md-and-up"
        @click="drawer = !drawer"
      ></v-app-bar-nav-icon>
    </v-app-bar>

    <!-- Mobile Navigation Drawer -->
    <v-navigation-drawer
      v-model="drawer"
      app
      temporary
      right
      class="hidden-md-and-up"
    >
      <v-list>
        <v-list-item class="px-2">
          <v-list-item-avatar>
            <v-icon large color="primary">mdi-bug</v-icon>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title class="text-h6">SAD Dengue</v-list-item-title>
            <v-list-item-subtitle>Sistema de Apoio</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <v-divider></v-divider>

      <v-list nav dense>
        <v-list-item
          v-for="item in menuItems"
          :key="item.path"
          :to="item.path"
          exact
          link
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
            <v-list-item-subtitle>{{ item.subtitle }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main>
      <router-view />
    </v-main>

    <!-- Footer -->
    <v-footer app color="grey lighten-3" padless>
      <v-col class="text-center caption py-2" cols="12">
        <v-icon small class="mr-1">mdi-hospital-building</v-icon>
        {{ new Date().getFullYear() }} — <strong>SAD Dengue</strong> — Sistema de Apoio à Decisão para Avaliação de Risco
        <v-icon small class="ml-2">mdi-shield-check</v-icon>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  name: 'App',

  data() {
    return {
      drawer: false,
      menuItems: [
        {
          title: 'Dashboard',
          subtitle: 'Estatísticas epidemiológicas',
          path: '/dashboard',
          icon: 'mdi-view-dashboard'
        },
        {
          title: 'Avaliação',
          subtitle: 'Nova avaliação de risco',
          path: '/avaliacao',
          icon: 'mdi-clipboard-pulse'
        },
        {
          title: 'Histórico',
          subtitle: 'Avaliações anteriores',
          path: '/historico',
          icon: 'mdi-history'
        }
      ]
    }
  }
}
</script>

<style>
/* Global styles */
.v-application {
  font-family: 'Roboto', sans-serif;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* Animation for route transitions */
.v-main {
  min-height: calc(100vh - 64px - 36px);
}
</style>
