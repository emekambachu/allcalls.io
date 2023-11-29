<script setup>
import { ref, reactive, defineEmits, computed, onMounted, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import VueTree from "@ssthouse/vue3-tree-chart";
import "@ssthouse/vue3-tree-chart/dist/vue3-tree-chart.css";
import axios from "axios";
let { userData, agentTreeModal, treeRoute } = defineProps({
    userData: Object,
    agentTreeModal: Boolean,
    treeRoute: String,
});
console.log('user data', userData);

let emit = defineEmits(["close"]);

let close = () => {
    emit("close");
};

const convertToTree = (agentData, parent) => {

    const tree = {
        name: parent.first_name + ' ' + parent.last_name,
        avatar: parent.profile_picture == null ? '/profile/avatar.png' : parent.profile_picture,
        children: [],
        identifier: 'id', // Change this to the unique identifier in your agent data
    };

    const buildTree = (agent, parentNode) => {
        const node = {
            name: `${agent.first_name} ${agent.last_name}`,
            avatar: agent.profile_picture == null ? '/profile/avatar.png' : agent.profile_picture,
            id: agent.id,
            // Add other properties you want to include in the node
        };

        if (parentNode) {
            parentNode.children = parentNode.children || [];
            parentNode.children.push(node);
        } else {
            tree.children.push(node);
        }

        if (agent.all_invitees && Array.isArray(agent.all_invitees) && agent.all_invitees.length > 0) {
            agent.all_invitees.forEach((childAgent) => {
                if (childAgent.all_invitees && Array.isArray(childAgent.all_invitees)) {
                    // If there are nested all_invitees, call buildTree recursively
                    buildTree(childAgent, node);
                } else {
                    // If no nested all_invitees, build the node directly
                    const childNode = {
                        name: `${childAgent.first_name} ${childAgent.last_name}`,
                        avatar: agent.profile_picture == null ? '/profile/avatar.png' : agent.profile_picture,
                        id: childAgent.id,
                        // Add other properties you want to include in the child node
                    };
                    node.children = node.children || [];
                    node.children.push(childNode);
                }
            });
        }
    };

    agentData.forEach((agent) => {
        buildTree(agent, null);
    });

    return tree;
};
let agentHierarchyData = ref(null)
const vehicules = ref(null);


onMounted(() => {
    fetchAgentTree()
})
let slidingLoader = ref(false)
//  Get Agent Tree
let fetchAgentTree = () => {
    slidingLoader.value = true
    axios.get(`${treeRoute}${userData.id}`)
        .then((res) => {
            console.log('res.data', res.data);
            const agentData = res.data.agentHierarchy.all_invitees; // Extract the all_invitees array
            vehicules.value = convertToTree(agentData, res.data.agentHierarchy);
            console.log('vehicules', vehicules.value);
            slidingLoader.value = false
        }).catch((error) => {
            console.log('erro', error);
        })
}
// const treeConfig = ref({ nodeWidth: 120, nodeHeight: 80, levelHeight: 200,orientation: 'horizontal', });
const treeConfig = ref({
    nodeWidth: 120,
    nodeHeight: 80,
    levelHeight: 200,
    orientation: 'horizontal', // Set the orientation to horizontal
});
const zoom = ref(1); // Initial zoom level
const treeRef = ref(null);
const zoomIn = () => {
    if (treeRef.value) {
        treeRef.value.zoomIn(); // Call the zoomIn method on the tree instance
    }
};

const zoomOut = () => {
    if (treeRef.value) {
        treeRef.value.zoomOut(); // Call the zoomOut method on the tree instance
    }
};
const restoreScale = () => {
    if (treeRef.value) {
        treeRef.value.restoreScale(); // Call the restoreScale method on the tree instance
    }
};
const handleWheel = (event) => {
    const delta = event.deltaY || event.detail || event.wheelDelta;

    if (delta < 0) {
        zoomIn(); // Zoom in on upward scroll
    } else {
        zoomOut(); // Zoom out on downward scroll
    }
};
const handleTouchMove = (event) => {
    // Handle touch move events (e.g., pinch-to-zoom on touchpad)
    const touchCount = event.touches.length;

    if (touchCount === 2) {
        // Pinch-to-zoom gesture detected
        const [touch1, touch2] = event.touches;
        const distance = Math.hypot(touch2.clientX - touch1.clientX, touch2.clientY - touch1.clientY);

        if (event._prevDistance) {
            const delta = distance - event._prevDistance;

            if (delta > 0) {
                zoomOut(); // Pinch out to zoom in
            } else if (delta < 0) {
                zoomIn(); // Pinch in to zoom out
            }
        }

        event._prevDistance = distance;
    }
};

</script>
<style scoped >
.zoom-controls {
    position: absolute;
    top: 12px;
    right: 40%;
}

.zoom-controls button {
    background-color: #d9d2d2;
    /* Green background */
    border: none;
    /* White text */
    padding: 5px 34px;
    /* Padding */
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
    /* Rounded corners */
}

.container {
    padding: 10px;
    overflow-x: auto;
    overflow-y: auto;
    /* Enable horizontal scrolling if needed */
    white-space: nowrap;
    /* Prevent nodes from wrapping to the next line */
}

.zoom-controls button:hover {
    background-color: #939393;
    /* Darker green on hover */
}

.rich-media-node {
    width: auto;
    padding: 20px;
    display: flex;
    text-align: center;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    /* color: white; */
    background-color: rgb(232, 240, 254);
    border-radius: 4px;
}

/* Styles for screens with a width between 601 and 900 pixels */
@media only screen and (min-width: 320px) and (max-width: 768px) {
    .zoom-controls {
        position: absolute;
        top: 68px;
        right: 10px;
    }
    .tree-container {
        margin-top: 40px;
    }
}
</style>
<template>
    <transition leave-active-class="duration-200">
        <div v-if="agentTreeModal" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
            <transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="agentTreeModal" class="fixed inset-0 transform transition-all" @click="close">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" />
                </div>
            </transition>

            <transition enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100" leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div v-if="agentTreeModal" style="width: 95%; height:100%;position:relative;"
                    class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
                    :class="maxWidthClass">
                    <div class="flex items-start justify-between p-4 border-b border-gray-300 rounded-t">
                        <h3 class="text-xl font-small text-gray-700">Agent Tree</h3>
                        <button @click="close" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                            data-modal-hide="defaultModal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="zoom-controls">
                        <button @click="zoomIn">+</button>
                        <button @click="zoomOut">-</button>
                        <button style="padding-bottom: 9px;padding-top: 7px;" class="btn-restore" @click="restoreScale">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                            </svg>

                        </button>
                    </div>
                    <vue-loader :slidingLoader="slidingLoader" />
                    <div class='container'>
                        <vue-tree ref="treeRef" style="width: 100%; height: 600px;" :direction="'horizontal'"
                            :style="{ transform: `scale(${zoom})`, 'transform-origin': '0 0' }" :dataset="vehicules"
                            :config="treeConfig" linkStyle="straight">
                            <template v-slot:node="{ node, collapsed }">
                                <div class="rich-media-node" :style="{ border: collapsed ? '2px solid grey' : '' }">
                                    <img v-if="node.avatar" :src="node.avatar" alt="Avatar"
                                        style="width: 32px; height: 32px; border-radius: 50%;" />
                                    <span style="padding: 4px 0; font-weight: bold;" class="text-black">{{ node.name
                                    }}</span>
                                </div>
                            </template>
                        </vue-tree>
                    </div>


                    <div class="flex justify-end p-5">
                        <PrimaryButton @click.prevent="close" type="button" class="ml-3">
                            Close
                        </PrimaryButton>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

