import {
    Content
}
from './Content/Content.js';

export class DocumentVersion {
    constructor(number,
        content,
        description,
        for_review,
        for_approval,
        reviewers,
        approvers,
        effective_date,
        expiry_date) {


        if (number !== undefined)
            this._number = number;
        else
            this._number = '';

        if (content !== undefined)
            this._content = content;
        else
            this._content = new Content;

        if (description !== undefined)
            this._description = description;
        else
            this._description = new Content;

        if (for_review !== undefined)
            this._for_review = for_review;
        else
            this._for_review = false;

        if (for_approval !== undefined)
            this._for_approval = for_approval;
        else
            this._for_approval = false;

        if (reviewers !== undefined)
            this._reviewers = reviewers;
        else
            this._reviewers = [];

        if (approvers !== undefined)
            this._approvers = approvers;
        else
            this._approvers = [];

        if (effective_date !== undefined)
            this._effective_date = effective_date;
        else
            this._effective_date = '';


        if (expiry_date !== undefined)
            this._expiry_date = expiry_date;
        else
            this._expiry_date = '';



      

    }

    toObject() {
        return {
            number: this.number,
            content: this.content,
            description: this.description,
            for_review: this.for_review,
            for_approval: this.for_approval,
            reviewers: this.reviewers,
            approvers: this.approvers,
            effective_date: this.effective_date,
            expiry_date: this.expiry_date,
        }
    }


 

    get expiry_date() {
        return this._expiry_date;
    }

    set expiry_date(expiry_date) {
        this._expiry_date = expiry_date;
    }

    get effective_date() {
        return this._effective_date;
    }

    set effective_date(effective_date) {
        this._effective_date = effective_date;
    }


    get approvers() {
        return this._approvers;
    }

    set approvers(approvers) {
        this._approvers = approvers;
    }


    get reviewers() {
        return this._reviewers;
    }

    set reviewers(reviewers) {
        this._reviewers = reviewers;
    }

    get for_approval() {
        return this._for_approval;
    }

    set for_approval(for_approval) {
        this._for_approval = for_approval;
    }

    get for_review() {
        return this._for_review;
    }

    set for_review(for_review) {
        this._for_review = for_review;
    }


    get description() {
        return this._description;
    }

    set description(description) {
        this._description = description;
    }

    get content() {
        return this._content;
    }

    set content(content) {
        this._content = content;
    }


    get number() {
        return this._number;
    }

    set number(number) {
        this._number = number;
    }


}
