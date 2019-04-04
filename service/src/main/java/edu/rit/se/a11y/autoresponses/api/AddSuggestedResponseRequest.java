package edu.rit.se.a11y.autoresponses.api;

public class AddSuggestedResponseRequest {

    private String context;
    private String suggestedResponse;

    public AddSuggestedResponseRequest(String context, String suggestedResponse) {
        this.context = context;
        this.suggestedResponse = suggestedResponse;
    }

    public String getContext() {
        return context;
    }

    public String getSuggestedResponse() {
        return suggestedResponse;
    }

}
