import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";

export function useInfinityTable(initialItems, initialUrl, filters, isLoadMoreEnabled = true) {

    console.log('Args passed to useInfinityTable from InfinityTable.js', {
        initialItems,
        initialUrl,
        filters,
        isLoadMoreEnabled
    });


    const loadedItems = ref(initialItems.data);
    const sortColumn = ref(null);
    const sortDirection = ref("asc");
    const sortingMethod = ref(null);


    console.log('Initial items from InfinityTable.js', initialItems.data);

    watch(
        () => initialItems,
        () => {
            console.log('intialItems value updated');
            loadedItems.value = [...loadedItems.value, ...initialItems.data];
        }
    );

    const loadMore = (url) => {
        if (!isLoadMoreEnabled) return;
    
        console.log('Load more called from InfinityTable.js');
        console.log('Next page url from InfinityTable.js', initialItems.next_page_url);
    
        router.get(
            initialItems.next_page_url,
            {},
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    console.log('Successfully navigated to the next page and ');
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
        if (!filters.value.length) {
            return loadedItems.value;
        }

        let items = loadedItems.value;

        filters.value.forEach((filter) => {
            if (filter.checked) {
                items = filter.filter(items);
            }
        });

        return items;
    });


    console.log('loadedItems from InfinityTable.js', loadedItems);
    console.log('Filtered items from InfinityTable.js', filteredItems);



    console.log('Intial items data from InfinityTable.js BEFORE RETURNING OBJECT, why is this empty??', initialItems.data);

    return {
        loadedItems,
        sortColumn,
        sortDirection,
        performSorting,
        sortByColumn,
        renderColumn,
        filteredItems: filters.length ? filteredItems : initialItems.data,
        loadMore
    };
}
