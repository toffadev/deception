<template>
    <div class="tinymce-wrapper">
        <div ref="editorElement" class="w-full"></div>
        <div v-if="showWordCount" class="mt-2 text-sm text-gray-500">
            Caractères: {{ characterCount }} / {{ minCharacters || 200 }} minimum
        </div>
    </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted, watch } from 'vue'

// Props
const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    disabled: {
        type: Boolean,
        default: false
    },
    minCharacters: {
        type: Number,
        default: 200
    },
    showWordCount: {
        type: Boolean,
        default: true
    }
})

// Emits
const emit = defineEmits(['update:modelValue', 'contentChange'])

// Refs
const editorElement = ref(null)
let editorInstance = null

// Computed pour le nombre de caractères
const characterCount = computed(() => {
    if (!props.modelValue) return 0
    const textContent = props.modelValue.replace(/<[^>]*>/g, '')
    return textContent.length
})

// Initialisation TinyMCE
onMounted(() => {
    if (window.tinymce && editorElement.value) {
        window.tinymce.init({
            target: editorElement.value,
            height: 400,
            menubar: false,
            plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link | help',
            setup: (editor) => {
                editorInstance = editor
                editor.on('input change', () => {
                    const content = editor.getContent()
                    emit('update:modelValue', content)
                    emit('contentChange', {
                        content: content,
                        characterCount: content.replace(/<[^>]*>/g, '').length,
                        isValid: content.replace(/<[^>]*>/g, '').length >= (props.minCharacters || 200)
                    })
                })
            }
        })
    }
})

onUnmounted(() => {
    if (editorInstance) {
        window.tinymce.remove(editorInstance)
    }
})

watch(() => props.modelValue, (newValue) => {
    if (editorInstance && newValue !== editorInstance.getContent()) {
        editorInstance.setContent(newValue || '')
    }
})
</script>

<style scoped>
.tinymce-wrapper {
    width: 100%;
}

/* Styles pour TinyMCE */
:deep(.tox-tinymce) {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
}

:deep(.tox-tinymce:focus-within) {
    outline: 2px solid transparent;
    outline-offset: 2px;
    box-shadow: 0 0 0 2px rgb(239 68 68 / 0.5);
    border-color: transparent;
}

:deep(.tox-toolbar) {
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

:deep(.tox-edit-area) {
    background-color: white;
}
</style>