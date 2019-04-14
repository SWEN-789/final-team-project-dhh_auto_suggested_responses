package edu.rit.se.a11y.autoresponses.api;

public class AddSuggestedResponseRequest {

    private String context;
    private String message;
    private String suggestedResponse;

    public AddSuggestedResponseRequest(String context, String message, String suggestedResponse) {
        this.context = context;
        this.message = message;
        this.suggestedResponse = suggestedResponse;
    }

    public String getContext() {
        return context;
    }

    public String getMessage() {
        return message;
    }

    public String getSuggestedResponse() {
        return suggestedResponse;
    }

}
