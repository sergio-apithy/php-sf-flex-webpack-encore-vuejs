import {Editors} from "./editors";
import {Authors} from "./authors";
import {Serie} from "./serie";
import {EntityAbstract} from "../abstract";

export class Book extends EntityAbstract {
    id: number
    title: string = ''
    description?: string = ''
    indexInSerie?: number

    editors: Array<Editors>
    authors: Array<Authors>
    serie?: Serie

    getOwnProps() {
        return ['title', 'description', 'indexInSerie', ]
    }

    addEdition(edition: Editors) {
        if (typeof this.editors == 'undefined') {
            this.editors = []
        }

        this.editors.push(edition)
    }

    addAuthor(author: Authors) {
        if (typeof this.authors == 'undefined') {
            this.authors = []
        }

        this.authors.push(author)
    }
}