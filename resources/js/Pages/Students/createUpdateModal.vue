<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    editing:{
        type: Boolean
    },
    form: {
        type: Object
    },
    working:{
        type:Boolean
    }
})

const form = useForm({
  id: 0,
  name: '',
  subject: '',
  marks: '',
  user_id: ''
});

onMounted(() => {
    if (props.form) {
        form.id = props.form.id;
        form.name = props.form.name;
        form.subject = props.form.subject;
        form.marks = props.form.marks;
        form.user_id = props.form.user_id;
    }
})

const emit = defineEmits(['handleClose', 'handleSubmit']);

</script>
<template>
  <div 
    class="fixed inset-0 bg-black bg-opacity-50 z-40"
  ></div>

    <!-- Main modal -->
    <div 
      id="crud-modal" 
      tabindex="-1"
      class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden"
    >
      <div class="relative w-full max-w-md p-4 max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <span v-if="editing">Edit Student</span>
                <span v-else>Add New Student</span>
            </h3>
            <button 
              type="button" 
              @click.prevent="$emit('handleClose')" 
              class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
              data-modal-toggle="crud-modal"
            >
              <svg 
                class="w-3 h-3" 
                aria-hidden="true" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 14 14"
              >
                <path 
                  stroke="currentColor" 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <!-- Modal body -->
          <form @submit.prevent="$emit('handleSubmit', form)" class="p-4 md:p-5">
          <div class="grid gap-4 mb-4 grid-cols-2">
            <div class="col-span-2">
              <label 
                for="name" 
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Full Name
              </label>
              <input 
                type="text" 
                v-model="form.name" 
                id="name" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                placeholder="Type full name"
              >
              <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
            </div>
            <div class="col-span-2">
              <label 
                for="subject" 
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Subject
              </label>
              <input 
                type="text" 
                v-model="form.subject" 
                id="subject" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                placeholder="Type subject" 
              >
              <span v-if="form.errors.subject" class="text-red-500 text-sm">{{ form.errors.subject }}</span>
            </div>
            <div class="col-span-2">
              <label 
                for="marks" 
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Marks
              </label>
              <input 
                type="number" 
                v-model="form.marks" 
                id="marks" 
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                placeholder="Type marks"
              >
              <span v-if="form.errors.marks" class="text-red-500 text-sm">{{ form.errors.marks }}</span>
            </div>
          </div>
          <button
            type="submit"
            :class="[
            'inline-flex items-center px-5 py-2.5 text-sm font-medium text-center rounded-lg',
            working ? 'bg-gray-500 cursor-wait' : 'bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
            working ? 'text-gray-300' : 'text-white',
            'focus:outline-none'
            ]"
            :disabled="working"
        >
            <span v-if="working">Submitting...</span>
            <span v-else>
            {{ editing ? 'Update Student' : 'Add New Student' }}
            </span>
        </button>
        </form>
        </div>
      </div>
    </div>
  </template>
  