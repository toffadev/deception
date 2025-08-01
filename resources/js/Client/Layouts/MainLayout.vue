<template>
  <div class="min-h-screen flex flex-col">
    <Header />
    
    <main class="flex-grow">
      <slot />
    </main>
    
    <Footer />
  </div>
</template>

<script setup>
import Header from '@client/Components/Header.vue'
import Footer from '@client/Components/Footer.vue'
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
}

.title-font {
  font-family: 'Playfair Display', serif;
}

.hero-gradient {
  background: linear-gradient(135deg, rgba(253,29,29,0.8) 0%, rgba(252,176,69,0.8) 100%);
}

.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.donation-progress {
  height: 8px;
  background-color: #e5e7eb;
}

.donation-progress-bar {
  height: 100%;
  background: linear-gradient(90deg, #f87171, #ef4444);
  transition: width 0.5s ease;
}

.partner-logo {
  filter: grayscale(100%);
  opacity: 0.7;
  transition: all 0.3s ease;
}

.partner-logo:hover {
  filter: grayscale(0);
  opacity: 1;
}
</style> 