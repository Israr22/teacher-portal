<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, Link, router} from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { onMounted } from 'vue';
import CreateUpdateModal from './createUpdateModal.vue';
const props = defineProps({
    students: {
        type: Object,
    },
    flash: {
        type: String
    },
    query: {
        type:String
    }

});

const query = ref(props.query);
const students = ref(props.students.data);
const pagination = ref(props.students);
const selectedStudents = ref([]);
const selectAll = ref(false);
const editing = ref(false);
const showModal = ref(false);
const working = ref(false);

const teacher = usePage().props.auth.user;

onMounted(() => {
    students.value = props.students.data;
    pagination.value = props.students;
    const input = document.querySelector('#table-search');
        if (props.query && input) {
            input.focus();
        }
})

const form = useForm({
    id: 0,
    name: '',
    subject: '',
    marks: '',
    user_id: teacher.id
});

let studentUrl = computed(()=>{
    let url  = new URL(route('student.index'));
    if(query.value)
        url.searchParams.append('query', query.value);
    return url;
})

watch(query, value => {
    router.visit(route('student.index', { query: value }), {
        replace: true,
        preserveScroll: true
    })
});

const toggleAll = () => {
    if (selectAll.value) {
        selectedStudents.value = students.value.map(student => student.id);
    } else {
        selectedStudents.value = [];
    }
}

const toggleSelection = (student) => {
    const index = selectedStudents.value.indexOf(student.id);
    if (index === -1) {
        selectedStudents.value.push(student.id);
    } else {
        selectedStudents.value.splice(index, 1);
    }
};

const deleteStudent = (student) => {
    const isConfirmed = window.confirm("Are you sure?");

    if (isConfirmed) {
        form.id = student.id;
        form.delete(route('student.store'), {
            onSuccess: () => {
                students.value = students.value.filter(stu => stu.id !== student.id);
                router.visit(route('student.index'))
            },
            onError: () => {
                console.error('Failed to delete the student.');
            }
        });
    }
};

const buldDelete = () => {
    const isConfirmed = window.confirm("Are you sure you want to delete the selected students?");

    if (isConfirmed && selectedStudents.value.length > 0) {
        const idsForm = useForm({
            ids: selectedStudents.value
        });

        idsForm.delete(route('student.bulkDestroy'), {
            onSuccess: () => {
                students.value = students.value.filter(student => !selectedStudents.value.includes(student.id));
                selectedStudents.value = [];
                selectAll.value = false;
                router.visit(route('student.index'))
            },
            onError: () => {
                console.error('Failed to delete the selected students.');
            }
        });
    }
}


const createStudent = () => {
    editing.value = false;
    form.reset();
    showModal.value = true;
}

const editStudent = (student) => {
    form.id = student.id;
    form.name = student.name;
    form.subject = student.subject;
    form.marks = student.marks;
    form.user_id = student.user_id;
    editing.value = true;
    showModal.value = true;
}

const getCurrentDateTime = () => {
    const now = new Date();

    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = now.getFullYear();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}

const submitForm = (form) => {
    working.value = true;
    form.post(route('student.store'), {
        onSuccess: () => {
            if (editing.value) {
                const studentToUpdate = students.value.find(student => student.id === form.id);
                updateStudent(studentToUpdate, form);
            } else {
                const studentToUpdate = students.value.find(student =>
                    student.name.toLowerCase() === form.name.toLowerCase() &&
                    student.subject.toLowerCase() === form.subject.toLowerCase()
                );
                updateStudent(studentToUpdate, form);
            }
            closeModal();
            working.value = false;
            router.visit(route('student.index'))
        },
        onError: () => {
            working.value = false;
        }
    });
}

const closeModal = () => {
    showModal.value = false;
}

const updateStudent = (studentToUpdate, form) => {
    if (studentToUpdate) {
        studentToUpdate.name = form.name;
        studentToUpdate.subject = form.subject;
        studentToUpdate.marks = form.marks;
    } else {
        students.value.unshift({
            name: form.name,
            subject: form.subject,
            marks: form.marks,
            created_at: getCurrentDateTime()
        });
    }
}

</script>

<template>

    <Head title="Student List" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Students</h2>
        </template>

        <div class="py-12">
            <div v-if="flash" class="alert alert-success">
                {{ flash }}
            </div>
            <CreateUpdateModal v-if="showModal" :working="working" :editing="editing" :form="form"
                @handle-close="closeModal" @handle-submit="submitForm" />
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div
                        class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between py-4">
                        <div class="flex">
                            <button type="button" @click="createStudent"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add
                                New</button>
                            <button v-if="selectedStudents.length" @click="buldDelete" type="button"
                                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete
                                selected {{ selectedStudents.length }}</button>
                        </div>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" v-model="query" id="table-search"
                            autocomplete="off"
                                class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search for items">
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-all-search" type="checkbox" v-model="selectAll"
                                            @change="toggleAll"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Student name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Subject
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Marks
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Register Date
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in students" :key="student.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-table-search-1" type="checkbox" :checked="selectAll"
                                            @change="toggleSelection(student)"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                    </div>
                                </td>
                                <th scope="row"
                                    class="py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex items-center gap-x-2">
                                        <div
                                            class="relative w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                                            <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd">
                                                </path>
                                            </svg>
                                        </div>
                                        <p>{{ student.name }}</p>
                                    </div>
                                </th>
                                <td class="px-6 py-4">
                                    {{ student.subject }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ student.marks }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ student.created_at }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex">
                                        <button @click="editStudent(student)" class="px-2">
                                            <svg class="w-6 h-6 text-blue-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                            </svg>
                                        </button>
                                        <button class="px-2" @click="deleteStudent(student)">
                                            <svg class="w-6 h-6 text-red-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 1 0 0 4h16a2 2 0 1 0 0-4H4Zm0 6h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8Zm10.707 5.707a1 1 0 0 0-1.414-1.414l-.293.293V12a1 1 0 1 0-2 0v2.586l-.293-.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l2-2Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between py-4 px-2">
                    <div>
                        <Link :href="pagination.prev_page_url?pagination.prev_page_url:'#'"
                            :class="{ 'cursor-not-allowed': !pagination.prev_page_url, 'pointer-events-none': !pagination.prev_page_url, 'opacity-25': !pagination.prev_page_url}"
                            class="flex items-center justify-center px-4 h-10 ms-3 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            Previous
                        </Link>
                    </div>
                    <div>
                        <span class="text-sm text-gray-700 dark:text-gray-400">
                            Showing <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.from }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.to }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ pagination.total }}</span> Entries
                        </span>
                    </div>
                    <div>
                        <Link :href="pagination.next_page_url? pagination.next_page_url: '#'"
                            :class="{ 'cursor-not-allowed': !pagination.next_page_url, 'pointer-events-none': !pagination.next_page_url, 'opacity-25': !pagination.next_page_url}"
                            class="flex items-center justify-center px-4 h-10 ms-3 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            Next
                        </Link>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
