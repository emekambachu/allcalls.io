import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";

export function useInfinityTable(initialProps, initialUrl, filters) {
    const loadedItems = ref(initialProps.calls.data);
    const sortColumn = ref(null);
    const sortDirection = ref("asc");
    const sortingMethod = ref(null);

    watch(
        () => initialProps.calls,
        () => {
            loadedItems.value = [...loadedItems.value, ...initialProps.calls.data];
        }
    );

    const loadMore = (url) => {
        router.get(
            initialProps.calls.next_page_url,
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    window.history.replaceState({}, "", initialUrl);
                },
            }
        );
    };

    const performSorting = () => {
        loadedItems.value.sort(sortingMethod.value);
    };

    const sortByColumn = (column) => {
        if (!column.sortable) return;

        if (sortColumn.value === column.label) {
            sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
            sortingMethod.value = column.sortingMethod;
        } else {
            sortColumn.value = column.label;
            sortDirection.value = "asc";
            sortingMethod.value = column.sortingMethod;
        }

        performSorting();
    };

    const renderColumn = (column, item) => {
        return column.render(item);
    };

    const filteredItems = computed(() => {
        let items = loadedItems.value;

        filters.value.forEach((filter) => {
            if (filter.checked) {
                items = filter.filter(items);
            }
        });

        return items;
    });

    return {
        loadedItems,
        sortColumn,
        sortDirection,
        performSorting,
        sortByColumn,
        renderColumn,
        filteredItems,
        loadMore
    };
}
