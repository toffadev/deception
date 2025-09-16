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

// Initialisation TinyMCE pour l'admin
onMounted(() => {
    if (window.tinymce && editorElement.value) {
        window.tinymce.init({
            target: editorElement.value,
            height: 500,
            menubar: 'edit view insert format tools table help',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code help',
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

/* Styles pour TinyMCE Admin */
:deep(.tox-tinymce) {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
}

:deep(.tox-tinymce:focus-within) {
    outline: 2px solid transparent;
    outline-offset: 2px;
    box-shadow: 0 0 0 2px rgb(59 130 246 / 0.5);
    border-color: transparent;
}

:deep(.tox-toolbar) {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

:deep(.tox-edit-area) {
    background-color: white;
}

:deep(.tox-menubar) {
    background-color: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
}
</style>