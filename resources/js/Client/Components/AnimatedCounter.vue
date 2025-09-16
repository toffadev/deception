<template>
  <span>{{ displayValue }}</span>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
  target: {
    type: Number,
    required: true
  },
  duration: {
    type: Number,
    default: 2000
  },
  isInView: {
    type: Boolean,
    default: false
  },
  suffix: {
    type: String,
    default: ''
  },
  prefix: {
    type: String,
    default: ''
  }
});

const currentValue = ref(0);
const animationId = ref(null);

const displayValue = computed(() => {
  return props.prefix + Math.round(currentValue.value).toLocaleString() + props.suffix;
});

const animateValue = () => {
  if (animationId.value) {
    cancelAnimationFrame(animationId.value);
  }
  
  const startTime = Date.now();
  const startValue = 0;
  const endValue = props.target;
  
  const animate = () => {
    const elapsed = Date.now() - startTime;
    const progress = Math.min(elapsed / props.duration, 1);
    
    // Utilisation d'une fonction d'easing (ease-out)
    const easeProgress = 1 - Math.pow(1 - progress, 3);
    
    currentValue.value = startValue + (endValue - startValue) * easeProgress;
    
    if (progress < 1) {
      animationId.value = requestAnimationFrame(animate);
    }
  };
  
  animationId.value = requestAnimationFrame(animate);
};

const resetValue = () => {
  if (animationId.value) {
    cancelAnimationFrame(animationId.value);
  }
  currentValue.value = 0;
};

watch(() => props.isInView, (newValue) => {
  if (newValue) {
    animateValue();
  } else {
    resetValue();
  }
});

watch(() => props.target, () => {
  if (props.isInView) {
    resetValue();
    setTimeout(animateValue, 100);
  }
});
</script>