function getTimeDifference(sqlDatetime) {
    const uploadedAt = new Date(sqlDatetime.replace(' ', 'T')); // Convert SQL datetime to JavaScript Date object
    const currentTime = new Date();
    
    const timeDifference = currentTime - uploadedAt;
    
    const minutesDifference = Math.floor(timeDifference / (1000 * 60));
    const hoursDifference = Math.floor(timeDifference / (1000 * 60 * 60));
    const daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
    const weeksDifference = Math.floor(daysDifference / 7);
    const monthsDifference = Math.floor(daysDifference / 30);
    const yearsDifference = Math.floor(daysDifference / 365);
    
    if (minutesDifference < 1) {
        return 'just now';
    } else if (minutesDifference < 60) {
        return minutesDifference + ' minute' + (minutesDifference === 1 ? '' : 's') + ' ago';
    } else if (hoursDifference < 24) {
        return hoursDifference + ' hour' + (hoursDifference === 1 ? '' : 's') + ' ago';
    } else if (daysDifference < 7) {
        return daysDifference + ' day' + (daysDifference === 1 ? '' : 's') + ' ago';
    } else if (daysDifference < 31) {
        return weeksDifference + ' week' + (weeksDifference === 1 ? '' : 's') + ' ago';
    } else if (monthsDifference < 12) {
        return monthsDifference + ' month' + (monthsDifference === 1 ? '' : 's') + ' ago';
    } else {
        return yearsDifference + ' year' + (yearsDifference === 1 ? '' : 's') + ' ago';
    }
}


