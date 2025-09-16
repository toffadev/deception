<template>
    <div class="tinymce-wrapper">
        <div ref="editorElement" class="tinymce-editor"></div>
        <div v-if="showWordCount" class="mt-2 text-sm text-gray-500">
            Caractères: {{ characterCount }} / {{ minCharacters || 200 }} minimum
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue'
import tinymce from 'tinymce/tinymce'

// Importer les thèmes et plugins nécessaires
import 'tinymce/themes/silver'
import 'tinymce/icons/default'
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/autolink'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/link'
import 'tinymce/plugins/charmap'
import 'tinymce/plugins/preview'
import 'tinymce/plugins/anchor'
import 'tinymce/plugins/searchreplace'
import 'tinymce/plugins/visualblocks'
import 'tinymce/plugins/code'
import 'tinymce/plugins/fullscreen'
import 'tinymce/plugins/insertdatetime'
import 'tinymce/plugins/media'
import 'tinymce/plugins/table'
import 'tinymce/plugins/help'
import 'tinymce/plugins/wordcount'
import 'tinymce/plugins/emoticons'

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
const editorInstance = ref(null)

// Computed pour le nombre de caractères
const characterCount = computed(() => {
    if (!props.modelValue) return 0
    const textContent = props.modelValue.replace(/<[^>]*>/g, '')
    return textContent.length
})

// Configuration TinyMCE locale
const editorConfig = {
    target: editorElement.value,
    height: 400,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
    ],
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
             'alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist outdent indent | removeformat | ' +
             'link emoticons | preview code fullscreen help',
    
    encoding: 'utf-8',
    entity_encoding: 'named',
    cleanup: true,
    verify_html: true,
    
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
    
    setup: (editor) => {
        editor.on('input change', () => {
            const content = editor.getContent()
            const cleanContent = sanitizeContent(content)
            emit('update:modelValue', cleanContent)
            emit('contentChange', {
                content: cleanContent,
                characterCount: cleanContent.replace(/<[^>]*>/g, '').length,
                isValid: cleanContent.replace(/<[^>]*>/g, '').length >= (props.minCharacters || 200)
            })
        })
    },
    
    paste_data_images: false,
    paste_webkit_styles: 'none',
    paste_retain_style_properties: 'color font-size font-family',
    
    skin: false,
    content_css: false
}

// Fonction de nettoyage du contenu
const sanitizeContent = (content) => {
    if (!content) return ''
    
    let cleaned = content.replace(/[^\u0020-\u007E\u00A0-\uFFFF]/g, '')
    cleaned = cleaned.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
    cleaned = cleaned.replace(/\son\w+\s*=\s*["'][^"']*["']/gi, '')
    
    return cleaned
}

// Lifecycle
onMounted(async () => {
    if (editorElement.value) {
        try {
            editorInstance.value = await tinymce.init({
                ...editorConfig,
                target: editorElement.value
            })
            
            // Définir le contenu initial
            if (props.modelValue) {
                editorInstance.value.setContent(props.modelValue)
            }
        } catch (error) {
            console.error('Erreur lors de l\'initialisation de TinyMCE:', error)
        }
    }
})

onUnmounted(() => {
    if (editorInstance.value) {
        tinymce.remove(editorInstance.value)
    }
})

// Watch pour les changements de modelValue
watch(() => props.modelValue, (newValue) => {
    if (editorInstance.value && newValue !== editorInstance.value.getContent()) {
        editorInstance.value.setContent(newValue || '')
    }
})
</script>

<style scoped>
.tinymce-wrapper {
    width: 100%;
}

.tinymce-editor {
    min-height: 400px;
}
</style>