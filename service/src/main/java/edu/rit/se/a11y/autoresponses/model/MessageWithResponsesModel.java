package edu.rit.se.a11y.autoresponses.model;

import java.util.ArrayList;
import java.util.List;

public class MessageWithResponsesModel {

    private String message;
    private List<String> suggestedResponses;

    public MessageWithResponsesModel() {
    }

    public MessageWithResponsesModel(String message, List<String> suggestedResponses) {
        this.message = message;
        this.suggestedResponses = suggestedResponses;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public List<String> getSuggestedResponses() {
        if (suggestedResponses == null) {
            suggestedResponses = new ArrayList<>();
        }
        return suggestedResponses;
    }

    public void setSuggestedResponses(List<String> suggestedResponses) {
        this.suggestedResponses = suggestedResponses;
    }

    public boolean addSuggestedResponse(String suggestedResponse) {
        return getSuggestedResponses().add(suggestedResponse);
    }

}
