export default class User {
    constructor() {

        this._id = '';
        this._name = '';
        this._email = '';
        this._username = '';
        this._position = '';
        this._password = '';
        this._created_by = '';
        this._edited_by = '';

        this._perm_administrator = false;
        this._perm_reviewer = false;
        this._perm_approver = false;

        this._remember_token = '';

        this._notify_changes = false;
        this._notify_followups = false;
        this._notify_comments = false;
        this._notify_reviewed = false;
        this._notify_approved = false;
        this._notify_to_approve = false;
        this._notify_to_review = false;

        this._created_at = '';
        this._updated_at = '';

    }
    get id() {
        return this._id;
    }
    get name() {
        return this._name;
    }
    get email() {
        return this._email;
    }
    get username() {
        return this._username;
    }
    get position() {
        return this._position;
    }
    get password() {
        return this._password;
    }
    get created_by() {
        return this._created_by;
    }
    get edited_by() {
        return this._edited_by;
    }
    get perm_administrator() {
        return this._perm_administrator;
    }
    get perm_reviewer() {
        return this._perm_reviewer;
    }
    get perm_approver() {
        return this._perm_approver;
    }
    get remember_token() {
        return this._remember_token;
    }
    get notify_changes() {
        return this._notify_changes;
    }
    get notify_followups() {
        return this._notify_followups;
    }
    get notify_comments() {
        return this._notify_comments;
    }
    get notify_reviewed() {
        return this._notify_reviewed;
    }
    get notify_approved() {
        return this._notify_approved;
    }
    get notify_to_approve() {
        return this._notify_to_approve;
    }
    get notify_to_review() {
        return this._notify_to_review;
    }
    get created_at() {
        return this._created_at;
    }
    get updated_at() {
        return this._updated_at;
    }

    set id(value) {
        this._id = value;
    }
    set name(value) {
        this._name = value;
    }
    set email(value) {
        this._email = value;
    }
    set username(value) {
        this._username = value;
    }
    set position(value) {
        this._position = value;
    }
    set password(value) {
        this._password = value;
    }
    set created_by(value) {
        this._created_by = value;
    }
    set edited_by(value) {
        this._edited_by = value;
    }
    set perm_administrator(value) {
        this._perm_administrator = value;
    }
    set perm_reviewer(value) {
        this._perm_reviewer = value;
    }
    set perm_approver(value) {
        this._perm_approver = value;
    }
    set remember_token(value) {
        this._remember_token = value;
    }
    set notify_changes(value) {
        this._notify_changes = value;
    }
    set notify_followups(value) {
        this._notify_followups = value;
    }
    set notify_comments(value) {
        this._notify_comments = value;
    }
    set notify_reviewed(value) {
        this._notify_reviewed = value;
    }
    set notify_approved(value) {
        this._notify_approved = value;
    }
    set notify_to_approve(value) {
        this._notify_to_approve = value;
    }
    set notify_to_review(value) {
        this._notify_to_review = value;
    }
    set created_at(value) {
        this._created_at = value;
    }
    set updated_at(value) {
        this._updated_at = value;
    }



}
