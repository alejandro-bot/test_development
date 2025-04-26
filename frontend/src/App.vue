<template>
  <div class="app-container">
    <router-view></router-view>
  </div>
</template>

<style>
.app-container {
  min-height: 100vh;
  background-color: #f5f5f5;
}

.nav-container {
  display: flex;
  justify-content: center;
  gap: 1rem;
  padding: 1rem;
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-link {
  text-decoration: none;
  color: #333;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.nav-link:hover {
  background-color: #f0f0f0;
}

.nav-link.router-link-active {
  background-color: #e3f2fd;
  color: #1976D2;
}
</style>
