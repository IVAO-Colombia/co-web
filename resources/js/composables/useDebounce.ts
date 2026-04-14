export const useDebounce = (callback: () => void, delay: number) => {
    let debounceTimer: ReturnType<typeof setTimeout>;

    return () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(callback, delay);
    };
};
