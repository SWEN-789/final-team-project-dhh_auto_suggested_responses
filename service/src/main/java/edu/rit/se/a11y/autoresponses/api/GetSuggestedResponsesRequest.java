package edu.rit.se.a11y.autoresponses.api;

public class GetSuggestedResponsesRequest {

    private String context;
    private String message;

    public GetSuggestedResponsesRequest(String context, String message) {
        this.context = context;
        this.message = message;
    }

    public String getContext() {
        return context;
    }

    public String getMessage() {
        return message;
    }

}
