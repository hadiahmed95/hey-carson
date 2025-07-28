export function formatDate(dateString: string, isShowTime = false): string {
    if (!dateString) return "";

    const date = new Date(dateString);

    const day = date.getDate().toString().padStart(2, "0");
    const month = date.toLocaleString("en-US", { month: "short" });
    const year = date.getFullYear();

    const datePart = `${day} ${month}, ${year}`;
    if (!isShowTime) return datePart;

    const timePart = date.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    }).toLowerCase();

    return `${datePart} / ${timePart}`;
}