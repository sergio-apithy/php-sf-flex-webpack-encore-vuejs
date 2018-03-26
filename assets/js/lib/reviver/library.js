const registryCache = {}
const registryEntity = {}
const propertyPath = []

/**
 * call it like this:
 *   mainBookReviver(JSON.parse(jsonString))
 *
 * @param key
 * @param value
 * @returns {Book}
 */
export function mainBookReviver(json) {
    const key = "book"
    const reviver = bookReviver

    if (Object.keys(json).contains(key)) {
        registry[key] = json[key]
        propertyPath.push(key)

        const rawRook = reviver(json)
        const book = new Book()
        for (let prop of rawBook) {
            book[prop] = rawBook[prop]
        }

        return book
    } else {
        console.warn ('woups, unknown props', key, value)
    }
}

export function bookReviver(json) {
    const ezProps = ["id", "title", "description"]
    const relProps = {
        "editors": editionsReviver,
        "authors": authorsReviver,
        "serie": serieReviver,
        "reviews": reviewsReviver
    }

    if (ezProps.contains(key)) {
        return value
    } else if (Object.keys(relProps).contains(key)) {
        return relProps[key](key, value)
    } else {
        console.warn ('woups, unknown props', key, value)
    }
}
