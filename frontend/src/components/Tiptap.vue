<template>
  <div>
    <div class="editor uk-position-relative" v-if="editor">
      <menu-bar v-if="active" class="uk-position-absolute editor__header uk-border-rounded" :editor="editor" />
      <editor-content class="editor__content" :class="{ 'uk-border-rounded': !active }" :editor="editor" />
      <div v-if="active" class="editor-actions uk-border-rounded">
        <a class="change_main_content uk-border-rounded" @click.prevent="setContentBlockQuery('main_content', editor.getHTML())">Сохранить изменения</a>
      </div>
    </div>
  </div>
</template>

<script>
import {watch, onMounted, ref, onUnmounted} from 'vue';
import { Editor, EditorContent } from '@tiptap/vue-2'
import StarterKit from '@tiptap/starter-kit'
import TaskList from '@tiptap/extension-task-list'
import TaskItem from '@tiptap/extension-task-item'
import Highlight from '@tiptap/extension-highlight'
import CharacterCount from '@tiptap/extension-character-count'
import MenuBar from './MenuBar.vue'
import store from '@/store';

export default {
  components: {
    EditorContent,
    MenuBar,
  },
  props: {
    active: {
      type: Boolean,
      default: false,
    },
  },
  setup(props, { emit }) {
    const editor = ref(null);

    watch(
      () => props.active,
      (v) => {
        if (editor.value) editor.value.setOptions({ editable: v });
      },
    );

    onMounted(() => {
      editor.value = new Editor({
        editable: false,
        content: store.getters.content.find(item => item.block === 'main_content').content,
        onUpdate: (value) => {
          store.dispatch('set_content_block', { block: 'main_content', content: value.editor.getHTML() })
        },
        extensions: [
          StarterKit.configure({
            history: false,
          }),
          Highlight,
          TaskList,
          TaskItem,
          CharacterCount.configure({
            limit: 10000,
          }),
        ]
      })
    })

    onUnmounted(() => {
      editor.value.destroy();
    })

    const setContentBlockQuery = async (block, content) => {
      try {
        await store.dispatch('set_content_block_query', { block, content });
        window.UIkit.notification('Вы успешно отредактировали заголовок!', {status: 'success', timeout: 2000})
        emit('update:active', false);
      } catch (err) {
        console.log('err', err);
        window.UIkit.notification('Что-то пошло не так', {status: 'warning', timeout: 2000})
      }
    }

    return { setContentBlockQuery, editor }
  },
}
</script>

<style lang="scss" scoped>
.editor-actions {
  padding: 6px 5px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  border: 2px solid #afafaf;
  border-top:0;
}


/* Custom */

.change_main_content {
  color:#3F4152;
  background: linear-gradient(180deg, #F5F5F5 0%, #D2D2D2 100%);
  border: 1px solid #7C7C7C;
  padding: 3px 7px;
  position: relative;
  border-radius: 0 0 0 4px;
}

.change_main_content:hover {
  background: linear-gradient(180deg, #e3e3e3 100%, #F5F5F5 0%);
  text-decoration:none;
}

/* Custom */
.editor {
  display: flex;
  width:1232px;
  flex-direction: column;
  //max-height: 400px;
  //color: #0D0D0D;
  //background-color: white;
  //border: 3px solid #0D0D0D;
  border-radius: 0.75rem;

  &__header {
    bottom: 100%;
    left:0;
    right:0;
    display: flex;
    align-items: center;
    flex: 0 0 auto;
    flex-wrap: wrap;
    padding: 0.25rem;
    border: 2px solid #afafaf;
    border-bottom:none;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
  }

  &__content {
    padding-left: 1rem;
    padding-right: 1rem;
    padding-top: 0.8rem;
    padding-bottom: 1.25rem;
    border: 2px solid #afafaf;
    flex: 1 1 auto;
    overflow-x: hidden;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  &__footer {
    display: flex;
    flex: 0 0 auto;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    white-space: nowrap;
    border-top: 3px solid #0D0D0D;
    font-size: 12px;
    font-weight: 600;
    color: #0D0D0D;
    white-space: nowrap;
    padding: 0.25rem 0.75rem;
  }

  /* Some information about the status */
  &__status {
    display: flex;
    align-items: center;
    border-radius: 5px;

    &::before {
      content: ' ';
      flex: 0 0 auto;
      display: inline-block;
      width: 0.5rem;
      height: 0.5rem;
      background: rgba(#0D0D0D, 0.5);
      border-radius: 50%;
      margin-right: 0.5rem;
    }

    &--connecting::before {
      background: #616161;
    }

    &--connected::before {
      background: #B9F18D;
    }
  }

  &__name {
    button {
      background: none;
      border: none;
      font: inherit;
      font-size: 12px;
      font-weight: 600;
      color: #0D0D0D;
      border-radius: 0.4rem;
      padding: 0.25rem 0.5rem;

      &:hover {
        color: #FFF;
        background-color: #0D0D0D;
      }
    }
  }
}
</style>

<style lang="scss">
/* Give a remote user a caret */
.collaboration-cursor__caret {
  position: relative;
  margin-left: -1px;
  margin-right: -1px;
  border-left: 1px solid #0D0D0D;
  border-right: 1px solid #0D0D0D;
  word-break: normal;
  pointer-events: none;
}

/* Render the username above the caret */
.collaboration-cursor__label {
  position: absolute;
  top: -1.4em;
  left: -1px;
  font-size: 12px;
  font-style: normal;
  font-weight: 600;
  //line-height: normal;
  user-select: none;
  color: #0D0D0D;
  padding: 0.1rem 0.3rem;
  border-radius: 3px 3px 3px 0;
  white-space: nowrap;
}

/* Basic editor styles */
.ProseMirror {
  > * + * {
    margin-top: 0.75em;
  }

  //ul,
  //ol {
  //  padding: 0 1rem;
  //}

  //h1,
  //h2,
  //h3,
  //h4,
  //h5,
  //h6 {
  //  line-height: 1.1;
  //}

  code {
    background-color: rgba(#616161, 0.1);
    color: #616161;
  }

  pre {
    background: #0D0D0D;
    color: #FFF;
    font-family: 'JetBrainsMono', monospace;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;

    code {
      color: inherit;
      padding: 0;
      background: none;
      font-size: 0.8rem;
    }
  }

  mark {
    background-color: #FAF594;
  }

  img {
    max-width: 100%;
    height: auto;
  }

  hr {
    margin: 1rem 0;
  }

  blockquote {
    padding-left: 1rem;
    border-left: 2px solid rgba(#0D0D0D, 0.1);
  }

  hr {
    border: none;
    border-top: 2px solid rgba(#0D0D0D, 0.1);
    margin: 2rem 0;
  }

  ul[data-type="taskList"] {
    list-style: none;
    padding: 0;

    li {
      display: flex;
      align-items: center;

      > label {
        flex: 0 0 auto;
        margin-right: 0.5rem;
      }
    }
  }
}
</style>
