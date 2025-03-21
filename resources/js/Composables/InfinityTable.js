import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";

export function useInfinityTable(props, initialItems, initialUrl, filters, isLoadMoreEnabled = true) {

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

    // let filteredItems = ref(initialItems.data);

    // apply filters to the original data:
    // let items = initialItems.data;

    // filters.forEach((filter) => {
    //     if (filter.checked) {
    //         items = filter.filter(items);
    //     }
    // });

    // filteredItems.value = items;



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

        console.log('Performing sorting for column', column.label, 'with sorting method', sortingMethod.value, ' and sort direction', sortDirection.value);

        performSorting();
    };

    const renderColumn = (column, item) => {
        return column.render(item);
    };


    console.log('loadedItems from InfinityTable.js', loadedItems);



    console.log('Intial items data from InfinityTable.js BEFORE RETURNING OBJECT, why is this empty??', initialItems.data);

    return {
        loadedItems,
        sortColumn,
        sortDirection,
        performSorting,
        sortByColumn,
        renderColumn,
        loadMore
    };
}
